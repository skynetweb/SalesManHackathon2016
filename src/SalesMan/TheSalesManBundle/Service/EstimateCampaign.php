<?php
/**
 * Created by PhpStorm.
 * User: alexandru.mituca
 * Date: 9/4/2016
 * Time: 7:02 AM
 */
namespace SalesMan\TheSalesManBundle\Service;

use SalesMan\TheSalesManBundle\Lib\ANN\Network;
use SalesMan\TheSalesManBundle\Lib\ANN\InputValue;
use SalesMan\TheSalesManBundle\Lib\ANN\OutputValue;
use SalesMan\TheSalesManBundle\Lib\ANN\StringValue;
use SalesMan\TheSalesManBundle\Lib\ANN\Values;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\Kernel;

class EstimateCampaign
{
    function __construct($rootDir)
    {

        $this->rootDir = $rootDir;
//        $path = realpath($rootDir . '/../');
//        $locator = new \Symfony\Component\Config\FileLocator($path);
//        $resource = $locator->locate('data.yml', null, false);
//        $this->data = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($resource[0]));
    }


    public function estimate()
    {

        try {
            $objNetwork = Network::loadFromFile($this->rootDir.'/data/campaigns.dat');
        } catch (\Exception $e) {
            die('Network not found');
        }

        try {
            $objCampaign = InputValue::loadFromFile($this->rootDir.'/data/input_category.dat');

            $objBudget = InputValue::loadFromFile($this->rootDir.'/data/input_budget.dat');
            $objCategory = InputValue::loadFromFile($this->rootDir.'/data/input_category.dat');
            $objDiscount = InputValue::loadFromFile($this->rootDir.'/data/input_discount.dat');
            $objStart = InputValue::loadFromFile($this->rootDir.'/data/input_start.dat');
            $objEnd = InputValue::loadFromFile($this->rootDir.'/data/input_end.dat');

            $objOrders = OutputValue::loadFromFile($this->rootDir.'/data/output_orders.dat');
            $objVisits = OutputValue::loadFromFile($this->rootDir.'/data/output_visits.dat');
            $objProdQ = OutputValue::loadFromFile($this->rootDir.'/data/output_prodq.dat');
        } catch (\Exception $e) {
            die('Error loading value objects');
        }

        try {
            $objValues = Values::loadFromFile($this->rootDir.'/data/values_campaigns.dat');
        } catch (\Exception $e) {
            die('Loading of values failed');
        }

        $objValues->input(
            $objCampaign->getInputValue('StockBusters'),
            $objCategory->getInputValue('Laptopuri'),
            $objDiscount->getInputValue(8),
            $objStart->getInputValue(147286),
            $objEnd->getInputValue(147294)
        );
        $objNetwork->setValues($objValues);
        $arrOutputs = $objNetwork->getOutputs();

        $values = [];
        foreach ($arrOutputs as $arrOutput)
            foreach ($arrOutput as $floatOutput)
                 array_push($values,  $objOrders->getRealOutputValue($floatOutput));

        return $values;
    }
}