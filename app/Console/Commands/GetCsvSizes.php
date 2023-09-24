<?php declare(strict_types=1);

namespace App\Console\Commands;

use DOMDocument;
use DOMXPath;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Faker\Extension\Extension;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetCsvSizes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:sizes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get CSV size data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * メルカリページからサイズの絞り込み情報をCSV で取得する
     * 
     * @return void
     */
    public function handle(): void
    {
        $options = new ChromeOptions();
        $caps = DesiredCapabilities::chrome();
        $caps->setCapability(ChromeOptions::CAPABILITY, $options);
        // $host = 'http://host.docker.internal:4444/wd/hub';
        $host = 'http://selenium:4444/wd/hub';

        try {
            $driver = RemoteWebDriver::create($host, $caps);
            $sizes = $this->getSizes($driver);
            $fp = fopen(storage_path() . '/app/local/seed/size.csv', 'w');
            foreach ($sizes as $size) {
                fputcsv($fp, $size);
            }
            fclose($fp);
            $driver->close();
        } catch (Extension $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * DOMで検索項目を取得(labelがWebDriverで取得できないため)
     *
     * @param \Facebook\WebDriver\Remote\RemoteWebDriver $driver
     * @return array
     */
    private function getSizes($driver): array
    {
        // ここでしか設定しません
        $NEXT_PAGE_WAIT = 1;

        $driver->get('https://jp.mercari.com/search');
        $driver->wait()->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::xpath("//*[@id='search-filter']/aside/div/div/div/ul/li[4]/mer-accordion/mer-select/div/div[1]/select"))
        );
        $html = $driver->getPageSource();
        $dom_xpath = $this->getDomXpath($html);

        $contents = $first_array = [];
        $xpath = "//*[@id='search-filter']/aside/div/div/div/ul/li[4]/mer-accordion/mer-select/div/div[1]/select/option";
        $row_count = count($dom_xpath->query("{$xpath}[not(@selected)]"));
        for ($i = 2; $i < $row_count + 2; $i++) {
            $id = $dom_xpath->query("{$xpath}[{$i}]/@value")->item(0)->textContent;
            $text = $dom_xpath->query("{$xpath}[{$i}]/text()")->item(0)->textContent;
            $contents[] = [$id, null, $text];
            $first_array[] = [$id, $text];
        }
        print_r($first_array);
        sleep($NEXT_PAGE_WAIT);

        foreach ($first_array as $first) {
            $driver->get("https://jp.mercari.com/search?size_id={$first[0]}");
            $driver->wait()->until(
                WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::xpath("//*[@id='search-filter']/aside/div/div/div/ul/li[4]/mer-accordion/mer-checkbox-group/mer-checkbox-label"))
            );
            $html = $driver->getPageSource();
            $dom_xpath = $this->getDomXpath($html);

            $xpath = "//*[@id='search-filter']/aside/div/div/div/ul/li[4]/mer-accordion/mer-checkbox-group/mer-checkbox-label";
            $row_count = count($dom_xpath->query("{$xpath}[mer-checkbox[@value!=0]]"));
            for ($i = 2; $i < $row_count + 2; $i++) {
                $id = $dom_xpath->query("{$xpath}[{$i}]/mer-checkbox/input/@value")->item(0)->textContent;
                $text = $dom_xpath->query("{$xpath}[{$i}]/mer-checkbox/input/@aria-label")->item(0)->textContent;
                $contents[] = [$id, $first[0], $text];
            }
            sleep($NEXT_PAGE_WAIT); // 負荷対策
        }

        return $contents;
    }

    /**
     * おまじない 必要理由は忘れた
     *
     * @param string $html
     * @return DOMXPath
     */
    private function getDomXpath($html) : DOMXPath
    {
        $dom = new DOMDocument;
        libxml_use_internal_errors(true); // libxmlエラーを無効 error防止
        $dom->loadHTML($html);
        $dom_xpath = new DOMXPath($dom);
        libxml_clear_errors(); // libxmlエラーハンドラをクリア error防止
        $dom_xpath->registerNamespace("php", "http://php.net/xpath");
        $dom_xpath->registerPHPFunctions();

        return $dom_xpath;
    }
}
