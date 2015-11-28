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
	
	//isMobile();
	public function testShouldReturnIfTheStringIsFromMobileDevice()
    {
		$string = [
					'Mozilla/5.0 (Linux; Android 5.1; XT1058 Build/LPA23.12-21.7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.76 Mobile Safari/537.36',
					'Mozilla/5.0 (iPhone; CPU iPhone OS 7_1_2 like Mac OS X) AppleWebKit/537.51.2 (KHTML, like Gecko) Version/7.0 Mobile/11D257 Safari/9537.53',
					'Mozilla/5.0 (iPad; CPU OS 8_3 like Mac OS X) AppleWebKit/600.1.4 (KHTML, like Gecko) Version/8.0 Mobile/12F69 Safari/600.1.4',
					'Mozilla/5.0 (iPad; CPU OS 8_3 like Mac OS X) AppleWebKit/600.1.4 (KHTML, like Gecko) CriOS/46.0.2490.85 Mobile/12F69 Safari/600.1.4',
					'Opera/9.80 (iPad; Opera Mini/11.0.0/37.7206; U; pt) Presto/2.12.423 Version/12.16',
				];
		foreach( $string as $k=>$v ){
			$_SERVER['HTTP_USER_AGENT'] = $v;
			$this->assertEquals( true , Utils::isMobile(false) );
		}
    }	
}