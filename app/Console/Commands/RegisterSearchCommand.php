<?php declare(strict_types=1);

namespace App\Console\Commands;

// 設定を間違えると大量のメールが送られるため制限
define('MAIL_TARGET_SEND_LIMIT', 3);

use App\Mail\Notification;
use App\Models\User;
use App\Models\SearchResultStocks;
use App\Models\SearchLists;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Faker\Extension\Extension;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RegisterSearchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:selenium';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'search product';

    private RemoteWebDriver $driver;

    /**
     * Selenium のdriver を作成する
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 検索対象から検索情報を取得してメールを送る
     *
     * @return void
     */
    public function handle(): void
    {
        $options = new ChromeOptions();
        $options->addArguments(['--no-sandbox', '--disable-dev-shm-usage']);
        $caps = DesiredCapabilities::chrome();
        $caps->setCapability(ChromeOptions::CAPABILITY, $options);
        $host = config('services.selenium.host');
        $this->driver = RemoteWebDriver::create($host, $caps);
        try {
            $search_lists = SearchLists::all()->toArray();
            foreach ($search_lists as $search_list) {
                $send_mail_count = 0;
                $base_url = 'https://jp.mercari.com/search?status=on_sale&';
                $search_exclusions = ['id', 'created_at', 'updated_at'];
                foreach ($search_list as $key => $value) {
                    $value = $this->getParameterById($key, $value);
                    if (is_null($value)) continue;
                    if (!in_array($key, $search_exclusions)) {
                        $base_url .= "{$key}={$value}&";
                    }
                }
                $base_url = substr($base_url, 0, -1);

                $this->driver->get($base_url);
                $this->driver->wait(10)->until(
                    WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('item-grid'))
                );
                $elements = $this->driver->findElements(WebDriverBy::xpath("//div[@id='item-grid']/ul/li"));
                foreach ($elements as $element) {
                    $product_name = $element->findElement(WebDriverBy::xpath("./div/a/div/span"))->getText();
                    $price = intval(str_replace(',', '', $element->findElement(WebDriverBy::xpath("./div/a/div/figure/div/div/span/span[2]"))->getText()));
                    $url = $element->findElement(WebDriverBy::xpath("./div/a"))->getAttribute('href');
                    $image_url = $element->findElement(WebDriverBy::xpath("./div/a/div/figure/div/picture/img"))->getAttribute('src');
                    $check = SearchResultStocks::where([
                        ['url', '=', $url]
                    ])->first();

                    if (is_null($check)) {
                        SearchResultStocks::create([
                            'search_lists_id' => $search_list['id'],
                            'product_name' => $product_name,
                            'price' => $price,
                            'url' => $url,
                            'image_url' => $image_url,
                        ])->deleteExcessRecords($search_list['id']);
                        if($send_mail_count < MAIL_TARGET_SEND_LIMIT){
                            $mailData = [
                                'name' => $product_name,
                                'price' => $price,
                                'url' => "https://jp.mercari.com{$url}",
                            ];

                            $email = User::where('id', $search_list['users_id'])->value('email');
                            Mail::to($email)->send(new Notification($mailData));
                            $send_mail_count += 1;
                        }
                    }
                }
            }
        } catch (Extension $e) {
            Log::error($e->getMessage());
        } finally{
            $this->driver->close();
            $this->driver->quit();
        }
    }

    /**
     * search_lists のid から検索条件を取得する
     *
     * @param string $key
     * @param integer|string|null $value
     * @return string|null
     */
    private function getParameterById(string $key, int|string|null $value) : string|null
    {
        switch ($key) {
            case 'category_id':
                $value = DB::table('categories')->where('value', $value)->value('category');
                break;
            case 'brand_id':
                $value = DB::table('brands')->where('value', $value)->value('brand');
                break;
            case 'size_id':
                $value = DB::table('sizes')->where('value', $value)->value('size');
                break;
            case 'item_condition_id':
                $value = DB::table('item_conditions')->where('value', $value)->value('item_condition');
                break;
            case 'shipping_payer_id':
                $value = DB::table('shipping_payers')->where('value', $value)->value('shipping_payer');
                break;
            case 'color_id':
                $value = DB::table('colors')->where('value', $value)->value('color');
                break;
            case 'shipping_method_id':
                $value = DB::table('shipping_methods')->where('value', $value)->value('shipping_method');
                break;
        }
        return $value;
    }
}
