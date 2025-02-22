<?php
/*
 * Copyright (c) 2011 Vantiv eCommerce Inc.
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */

namespace cnp\sdk\Test\unit;
use cnp\sdk\CnpOnlineRequest;
use cnp\sdk\CommManager;

class EcheckSaleUnitTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CommManager::reset();
    }

    public function test_simple_echeckSale()
    {
        $hash_in = array('cnpTxnId' =>'123123','id' => 'id');
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*<cnpTxnId>123123.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->echeckSaleRequest($hash_in);
    }

    public function test_both_choices()
    {
        $hash_in = array('reportGroup'=>'Planets','cnpTxnId'=>'123456','id' => 'id',
        'echeckToken' => array('accType'=>'Checking','routingNum'=>'123123','cnpToken'=>'1234565789012','checkNum'=>'123455'),
        'echeck' => array('accType'=>'Checking','routingNum'=>'123123','accNum'=>'12345657890','checkNum'=>'123455'));
        $cnpTest = new CnpOnlineRequest();
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $retOb = $cnpTest->echeckSaleRequest($hash_in);
    }

    public function test_loggedInUser()
    {
        $hash_in = array(
                'cnpTxnId' =>'123123',
        		'id' => 'id',
                'merchantSdk'=>'PHP;10.1.0',
                'loggedInUser'=>'gdake');
        $mock = $this->getMock('cnp\sdk\CnpXmlMapper');
        $mock->expects($this->once())
        ->method('request')
        ->with($this->matchesRegularExpression('/.*merchantSdk="PHP;10.1.0".*loggedInUser="gdake" xmlns=.*>.*/'));

        $cnpTest = new CnpOnlineRequest();
        $cnpTest->newXML = $mock;
        $cnpTest->echeckSaleRequest($hash_in);
    }
    
    public function test_echeck_sale_secondaryAmount()
    {
    	$hash_in = array(
                'id' => 'id',
    			'orderId' => '12',
                'amount' => '5000',
    			'secondaryAmount' => '2000',
    			'orderSource'=>'ecommerce',
    			'billToAddress' => array(),
    			'echeck' => array('accType'=>'Checking','routingNum'=>'123123123','accNum'=>'12345657890','checkNum'=>'123455','echeckCustomerId'=>'1234567','accountId'=>'012345'));
    	
    	$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
    	$mock->expects($this->once())
    	->method('request')
    	->with($this->matchesRegularExpression('/.*<secondaryAmount>2000.*<accType>Checking.*<accNum>12345657890.*<routingNum>123123123.*<checkNum>123455.*<echeckCustomerId>1234567.*<accountId>012345.*/'));
    	//'/.*<cnpTxnId>12.*<orderSource>ecommerce.*<accType>Checking.*<accNum>12345657890.*<routingNum>123123.*<checkNum>123455.*/'
    	$cnpTest = new CnpOnlineRequest();
    	$cnpTest->newXML = $mock;
    	$cnpTest->echeckSaleRequest($hash_in);
    }
    
    public function test_simple_customIdentifier()
    {
    	$hash_in = array('cnpTxnId' =>'123123','id' => 'id','amount'=>'123','customIdentifier'=>'identifier');
    	$mock = $this->getMock('cnp\sdk\CnpXmlMapper');
    	$mock->expects($this->once())
    	->method('request')
    	->with($this->matchesRegularExpression('/.*<cnpTxnId>123123.*<customIdentifier>identifier.*/'));
    
    	$cnpTest = new CnpOnlineRequest();
    	$cnpTest->newXML = $mock;
    	$cnpTest->echeckSaleRequest($hash_in);
    }

}
