<?php namespace Webunion\Utils;

require_once( dirname(__DIR__) . '/src/Utils.php');

use Webunion\Utils\Utils;

class UtilsTest extends \PHPUnit_Framework_TestCase
{

	public function setUp()
    {
        if (!class_exists('Webunion\\Utils\\UtilsTest')) {
            $this->markTestSkipped('UtilsTest was not installed.');
        }
    }

	//objectToArray();
	public function testShouldConvertObjectToArray()
    {
		$obj = new \StdClass();
		$obj->test = 'teste';
		$obj->test2 = 'teste2';
		
		$test = Utils::objectToArray($obj);

		$this->assertEquals( ['test'=>'teste','test2'=>'teste2'] , $test );
    }

	//stringToUrl();
	public function testShouldConvertStringToUrl()
    {
		$string = 'convert # to a @ pretty ! url 123 ';
		$expected = 'convert-to-a-pretty-url-123';
		
		$test = Utils::stringToUrl($string);

		$this->assertEquals( $expected , $test );
    }	
	
	
	//filterOnlyNumbers();
	public function testShouldReturnOnlyNumbers()
    {
		$string = 'iya1a5a8alahgat8a9a4';
		$expected = '158894';
		
		$test = Utils::filterOnlyNumbers($string);

		$this->assertEquals( $expected , $test );
    }	
	
	
	//encrypt();
	public function testShouldBeEncrypted()
    {
		$string = 'TextShouldBeEncrypted';
		$expected = 'SKxP1pbNgK0oirOFKNmXvFdSrMDaH48yalyjdqxtH6A';
		
		$test = Utils::encrypt($string, 'SECRETKEY' );

		$this->assertEquals( $expected , $test );
    }
	
	//decrypt();
	public function testShouldBeDecrypted()
    {
		$string = 'SKxP1pbNgK0oirOFKNmXvFdSrMDaH48yalyjdqxtH6A';
		$expected = 'TextShouldBeEncrypted';
				
		$test = Utils::decrypt($string, 'SECRETKEY' );

		$this->assertEquals( $expected , $test );
    }	
	
	//urlsafeB64Encode();
	public function testShouldReturnUrlSafeBase64EncodedString()
    {
		$string 	= 'TextShouldBeEncrypted';
		$expected 	= 'VGV4dFNob3VsZEJlRW5jcnlwdGVk';
		
		$test = Utils::urlsafeB64Encode($string);

		$this->assertNotContains( '+' , $test );
		$this->assertNotContains( '/' , $test );
		$this->assertEquals( $expected , $test );
    }	
	
	//decrypt();
	public function testShouldReturnStringConvertedFromUrlSafeBase64EncodedString()
    {
		$string 	= 'VGV4dFNob3VsZEJlRW5jcnlwdGVk';
		$expected 	= 'TextShouldBeEncrypted';
		
		$test = Utils::urlsafeB64Decode($string);

		$this->assertEquals( $expected , $test );
    }
	
	
}