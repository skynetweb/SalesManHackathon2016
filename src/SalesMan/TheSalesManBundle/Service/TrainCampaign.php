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

class TrainCampaign
{
    function __construct($rootDir)
    {

        $this->rootDir = $rootDir;
        $path = realpath($rootDir . '/../');

        $locator = new \Symfony\Component\Config\FileLocator($path);
        $resource = $locator->locate('data.yml', null, false);
        $this->data = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($resource[0]));
    }

    public function train()
    {

        try {
            $objNetwork = Network::loadFromFile($this->rootDir.'/data/campaigns.dat');
        } catch (\Exception $e) {
            print 'Creating a new one...';

            $objNetwork = new Network(2, 5, 2);

            $objCampaign = new StringValue(15);
            $objCategory = new StringValue(15);
            $objDiscount = new InputValue(0, 15);
            $objBudget = new InputValue(0, 100);
            $objStart = new InputValue(140000, 159999);
            $objEnd = new InputValue(140000, 159999);

            $objCampaign->saveToFile($this->rootDir.'/data/input_category.dat');
            $objCategory->saveToFile($this->rootDir.'/data/input_category.dat');
            $objDiscount->saveToFile($this->rootDir.'/data/input_discount.dat');
            $objBudget->saveToFile($this->rootDir.'/data/input_budget.dat');
            $objStart->saveToFile($this->rootDir.'/data/input_start.dat');
            $objEnd->saveToFile($this->rootDir.'/data/input_end.dat');


            $objOrders = new OutputValue(30, 100);
            $objVisits = new OutputValue(80, 150);
            $objProdQ = new OutputValue(30, 200);

            $objOrders->saveToFile($this->rootDir.'/data/output_orders.dat');
            $objVisits->saveToFile($this->rootDir.'/data/output_visits.dat');
            $objProdQ->saveToFile($this->rootDir.'/data/output_prodq.dat');


            $objValues = new Values;

            foreach ($this->data as $key => $value) {
                $objValues->train()
                    ->input(
                        $objCampaign->getInputValue($value['campaign']),
                        $objCategory->getInputValue($value['category']),
                        $objDiscount->getInputValue($value['discount']),
                        $objStart->getInputValue($value['start']),
                        $objEnd->getInputValue($value['end'])
                    )
                    ->output(
                        $objVisits->getOutputValue($value['visits']),
                        $objOrders->getOutputValue($value['orders'])
                    );
            }

            $objValues->saveToFile($this->rootDir.'/data/values_campaigns.dat');

            unset($objCampaign);
            unset($objCategory);
            unset($objBudget);
            unset($objDiscount);
            unset($objOrders);
            unset($objVisits);
            unset($objProdQ);
            unset($objStart);
            unset($objEnd);
        }

        try {
            $objCampaign = InputValue::loadFromFile($this->rootDir.'/data/input_category.dat');

            $objBudget = InputValue::loadFromFile($this->rootDir.'/data/input_budget.dat');
            $objDiscount = InputValue::loadFromFile($this->rootDir.'/data/input_discount.dat');
            $objCategory = InputValue::loadFromFile($this->rootDir.'/data/input_category.dat');
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

        $objNetwork->setValues($objValues); // to be called as of version 2.0.6

        $boolTrained = $objNetwork->train();

        print ($boolTrained)
            ? 'Network trained'
            : 'Network not trained completely. Please re-run the script';

        $objNetwork->saveToFile($this->rootDir.'/data/campaigns.dat');
    }
}