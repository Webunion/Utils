<?php namespace Webunion\Utils;

class Utils
{	
	CONST VERSION = 2.0;

	//Converte object para array
    public static function objectToArray($object){
		if(!is_object($object ) && !is_array($object)){
			return $object;
		}
		if(is_object($object)){
			$object = get_object_vars($object);
		}
		if(is_array($object)){
			return array_map('\Helper::'.__FUNCTION__, $object);
		}
		else{
			return $object;
		}
	}

    //Trata uma string para uma URL
    public static function stringToUrl($var){
		$a = array("á","à","ã","â","Á","À","Ã","Â");
		$e = array("é","è","ê","É","È","Ê");
		$i = array("í","ì","î","Í","Ì","Î");
		$o = array("ó","ò","ô","õ","Ó","Ò","Ô","Õ","º");
		$u = array("ú","ù","û","Ú","Ù","Û");
		$sign1 = array(" - ", " ", "--", "---", "-");
		$signs = array("~","´","`","/","<",">","?","!","@","#","$",",",".",";",":","$","%","¨","&","*","(",")","_","=","+","|","º","'", "\"");

		mb_strtolower($var);
		$var = str_replace($a,"a",$var);
		$var = str_replace($e,"e",$var);
		$var = str_replace($i,"i",$var);
		$var = str_replace($o,"o",$var);
		$var = str_replace($u,"u",$var);
		$var = str_replace("ç","c",$var);
		$var = str_replace($signs,"",$var);
		$var = str_replace($sign1,"-",$var);
		$var = strtolower($var);

		return $var;
	}
	
    //Remove letras
    public static function filterOnlyNumbers($var){
		$var = preg_replace('/[^0-9]/', '', $var);
		return $var;
    }

    //Retorna o IP de uma requisicao
    public static function getIp(){
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
            return getenv("HTTP_CLIENT_IP");
        }
        elseif (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
            return getenv("HTTP_X_FORWARDED_FOR");
        }
        elseif (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
            return getenv("REMOTE_ADDR");
        }
        elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
            return $_SERVER['REMOTE_ADDR'];
        }
        else{
            return "unknown";
        }
    }

    //Encriptar string
    public static function encrypt($string, $key = '123456ABC'){
        return self::urlsafeB64Encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
    }
	
    //Decriptar string
    public static function decrypt($string, $key = '123456ABC'){
        return  rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), self::urlsafeB64Decode($string), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
    }
	
	 /**
     * Decode a string with URL-safe Base64.
     *
     * @param string $input A Base64 encoded string
     *
     * @return string A decoded string
     */
	public static function urlsafeB64Decode($input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }
	
     /**
     * Encode a string with URL-safe Base64.
     *
     * @param string $input The string you want encoded
     *
     * @return string The base64 encode of what you passed in
     */
    public static function urlsafeB64Encode($input)
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }
	
	//Trata um texto para aceitar caracteres seguros
	public static function safeText($var){
		$restrito = array("~","´","`","/",".'\'.","<",">","?","!",",",";",":",
		"%","¨","&","(","(","=","+","javascript","'",'"');
		$var = str_replace($restrito,"",strip_tags($var));
		return $var;
	}
	
	//Trata um texto para ser um nome de controller camel case
	public static function controllerText($str){
		$str = str_replace('-', ' ', $str);
		//$str = (str_word_count($str) < 2) ? $str.'Index' : $str;
		$str = str_replace(' ', '', ucwords($str));
		return $str;
	}
	
	//Converte uma data em outro formato
	public static function dateConvert($date = '01/01/00', $formatIn = 'd/m/y', $formatOut = 'Y-m-d'){
		return \DateTime::createFromFormat($formatIn, $date)->format($formatOut);
	}

	//Analisa uma data SABRE e retorna o seu ano (com base em uma data de entrada com o ano
	public static function sabreCreateDate($dateRef = '2000-01-01', $date = '04-26T01:35', $formatOut = 'Y-m-d H:m'){
		$dateRef = explode('-', $dateRef);
		if($dateRef[1] > substr($date,0,2)){
			$date = ($dateRef[0] + 1).'-'.str_replace('T','',$date);
		}
		else{
			$date = ($dateRef[0]).'-'.str_replace('T','',$date);
		}
		return \DateTime::createFromFormat('Y-m-d H:i', $date)->format($formatOut);
	}
	
	//Faz calculos sobre datas
	public static function dateCalc($date, $calc ='+1 day', $formatOut = 'Y-m-d'){
		return date($formatOut, strtotime($calc, strtotime($date))); 
	}
	
	//Converte uma data de 1000 para 10:
	public static function amadeusTime($data){
		return substr($data, 0, 2).':'.substr($data, 2, 2);
	}

	//Gera um link para CSS
	public static function linkCss($data){
		$data = '<link rel="stylesheet" type="text/css" href="'.$data.'" />';
		return $data;
	}
	
	//Gera um link para Javascript
	public static function linkJs($data){
		$data = '<script type="text/javascript" src="'.$data.'"></script>';
		return $data;
	}

	//Gera um link para Javascript
	public static function inlineJs($data){
		$data = '<script type="text/javascript">'.$data.'</script>';
		return $data;
	}
	
	//Pega o conteudo de um template e armazena em uma variavel
	public static function getTemplateContent($template){
		if(empty($template)){
			return 'Informe o template';
		}
		else{
			if(!($return = file_get_contents(APP_VIEWS.'template'.DS.$template))){
				return 'Impossivel ler o template'.APP_VIEWS.$template;
			}
			else{
				return $return; 			
			}
		}
	}
	
	//Pega o conteudo de um script e armazena em uma variavel
	public static function getScriptContent($file){
		if(empty($file)){
			return 'Informe o file';
		}
		else{
			$file = str_replace('/', DS, $file);		
			if(!($return = file_get_contents(APP_SCRIPTS.$file))){
				return 'Impossivel ler o template'.APP_VIEWS.$file;
			}
			else{
				return $return; 			
			}
		}
	}
	
	//Normaliza um array para usar em um foreach, se for simles, coloca um [0]
	public static function arrayNormalize(&$array){
		if(is_array($array)){
			if(!array_key_exists(0, $array)){
				$array = array(0=>$array);
				return $array;
			}
			else{
				return $array;
			}
		}
		else{
			return false;
		}		
	}
	
	//Verifica se uma key existe, se sim, retorna seu valor, do contrario, retorna null
	public static function arrayCheckKey($key = '', $array = Array()){
		if(array_key_exists($key, $array)){
			return $array[$key];
		}
		else{
			return false;
		}				
	}
	
	//Imprime os valores dos elementos de um array
	public static function arrayPrint($array, $break = ' '){
		if(is_array($array)){
			$return = '';
			foreach($array as $value){
				$return .= $value.$break;
			}
			return $return;
		}
		else{
			return $array;
		}		
	}
	
	//Verifica se uma chave existe no array e retorna o seu valor
	public static function arrayKeyValue(&$array, $nullValue = null){
		if(isset($array)){
			if(is_array($array)){
				return $array;
			}
			else{
				return $array;
			}
		}
		else{
			return $nullValue;
		}		
	}

	
	//Verifica se o acesso esta sendo feito de um celular ou dispositivo movel
	public static function isMobile(){
		$mobile_browser   = false; // set mobile browser as false till we can prove otherwise
		$user_agent       = (array_key_exists('HTTP_USER_AGENT', $_SERVER) ? $_SERVER['HTTP_USER_AGENT'] : ''); // get the user agent value - this should be cleaned to ensure no nefarious input gets executed
		$accept           = (array_key_exists('HTTP_ACCEPT', $_SERVER) ? $_SERVER['HTTP_ACCEPT'] : ''); // get the content accept value - this should be cleaned to ensure no nefarious input gets executed
		switch(true){ // using a switch against the following statements which could return true is more efficient than the previous method of using if statements
			case (preg_match('/(ipod|iphone|ipad)/i',$user_agent)); // we find the words iphone or ipod in the user agent
			  $mobile_browser = true; // mobile browser is either true or false depending on the setting of iphone when calling the function
			break;
			case (preg_match('/(android)/i',$user_agent));  // we find android in the user agent
			  $mobile_browser = true; // mobile browser is either true or false depending on the setting of android when calling the function
			break; // break out and skip the rest if we've had a match on android

			case (preg_match('/(opera mini)/i',$user_agent)); // we find opera mini in the user agent
			  $mobile_browser = true; // mobile browser is either true or false depending on the setting of opera when calling the function
			break; // break out and skip the rest if we've had a match on opera

			case (preg_match('/(BlackBerry)/i',$user_agent)); // we find blackberry in the user agent
			  $mobile_browser = true; // mobile browser is either true or false depending on the setting of blackberry when calling the function
			break; // break out and skip the rest if we've had a match on blackberry

			case (preg_match('/(palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine)/i',$user_agent)); // we find palm os in the user agent - the i at the end makes it case insensitive
			  $mobile_browser = true; // mobile browser is either true or false depending on the setting of palm when calling the function
			break; // break out and skip the rest if we've had a match on palm os

			case (preg_match('/(windows ce; ppc;|windows ce; smartphone;|windows ce; iemobile)/i',$user_agent)); // we find windows mobile in the user agent - the i at the end makes it case insensitive
			  $mobile_browser = true; // mobile browser is either true or false depending on the setting of windows when calling the function
			break; // break out and skip the rest if we've had a match on windows

			case (preg_match('/(up.browser|up.link|BlackBerry|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|pda|psp|treo)/i',$user_agent)); // check if any of the values listed create a match on the user agent - these are some of the most common terms used in agents to identify them as being mobile devices - the i at the end makes it case insensitive
			  $mobile_browser = true; // set mobile browser to true
			break; // break out and skip the rest if we've preg_match on the user agent returned true 

			case ((strpos($accept,'text/vnd.wap.wml')>0)||(strpos($accept,'application/vnd.wap.xhtml+xml')>0)); // is the device showing signs of support for text/vnd.wap.wml or application/vnd.wap.xhtml+xml
			  $mobile_browser = true; // set mobile browser to true
			break; // break out and skip the rest if we've had a match on the content accept headers

			case (isset($_SERVER['HTTP_X_WAP_PROFILE'])||isset($_SERVER['HTTP_PROFILE'])); // is the device giving us a HTTP_X_WAP_PROFILE or HTTP_PROFILE header - only mobile devices would do this
			  $mobile_browser = true; // set mobile browser to true
			break; // break out and skip the final step if we've had a return true on the mobile specfic headers

			case(in_array(strtolower(substr($user_agent,0,4)),array('1207'=>'1207','3gso'=>'3gso','4thp'=>'4thp','501i'=>'501i','502i'=>'502i','503i'=>'503i','504i'=>'504i','505i'=>'505i','506i'=>'506i','6310'=>'6310','6590'=>'6590','770s'=>'770s','802s'=>'802s','a wa'=>'a wa','acer'=>'acer','acs-'=>'acs-','airn'=>'airn','alav'=>'alav','asus'=>'asus','attw'=>'attw','au-m'=>'au-m','aur '=>'aur ','aus '=>'aus ','abac'=>'abac','acoo'=>'acoo','aiko'=>'aiko','alco'=>'alco','alca'=>'alca','amoi'=>'amoi','anex'=>'anex','anny'=>'anny','anyw'=>'anyw','aptu'=>'aptu','arch'=>'arch','argo'=>'argo','bell'=>'bell','bird'=>'bird','bw-n'=>'bw-n','bw-u'=>'bw-u','beck'=>'beck','benq'=>'benq','bilb'=>'bilb','blac'=>'blac','c55/'=>'c55/','cdm-'=>'cdm-','chtm'=>'chtm','capi'=>'capi','comp'=>'comp','cond'=>'cond','craw'=>'craw','dall'=>'dall','dbte'=>'dbte','dc-s'=>'dc-s','dica'=>'dica','ds-d'=>'ds-d','ds12'=>'ds12','dait'=>'dait','devi'=>'devi','dmob'=>'dmob','doco'=>'doco','dopo'=>'dopo','el49'=>'el49','erk0'=>'erk0','esl8'=>'esl8','ez40'=>'ez40','ez60'=>'ez60','ez70'=>'ez70','ezos'=>'ezos','ezze'=>'ezze','elai'=>'elai','emul'=>'emul','eric'=>'eric','ezwa'=>'ezwa','fake'=>'fake','fly-'=>'fly-','fly_'=>'fly_','g-mo'=>'g-mo','g1 u'=>'g1 u','g560'=>'g560','gf-5'=>'gf-5','grun'=>'grun','gene'=>'gene','go.w'=>'go.w','good'=>'good','grad'=>'grad','hcit'=>'hcit','hd-m'=>'hd-m','hd-p'=>'hd-p','hd-t'=>'hd-t','hei-'=>'hei-','hp i'=>'hp i','hpip'=>'hpip','hs-c'=>'hs-c','htc '=>'htc ','htc-'=>'htc-','htca'=>'htca','htcg'=>'htcg','htcp'=>'htcp','htcs'=>'htcs','htct'=>'htct','htc_'=>'htc_','haie'=>'haie','hita'=>'hita','huaw'=>'huaw','hutc'=>'hutc','i-20'=>'i-20','i-go'=>'i-go','i-ma'=>'i-ma','i230'=>'i230','iac'=>'iac','iac-'=>'iac-','iac/'=>'iac/','ig01'=>'ig01','im1k'=>'im1k','inno'=>'inno','iris'=>'iris','jata'=>'jata','java'=>'java','kddi'=>'kddi','kgt'=>'kgt','kgt/'=>'kgt/','kpt '=>'kpt ','kwc-'=>'kwc-','klon'=>'klon','lexi'=>'lexi','lg g'=>'lg g','lg-a'=>'lg-a','lg-b'=>'lg-b','lg-c'=>'lg-c','lg-d'=>'lg-d','lg-f'=>'lg-f','lg-g'=>'lg-g','lg-k'=>'lg-k','lg-l'=>'lg-l','lg-m'=>'lg-m','lg-o'=>'lg-o','lg-p'=>'lg-p','lg-s'=>'lg-s','lg-t'=>'lg-t','lg-u'=>'lg-u','lg-w'=>'lg-w','lg/k'=>'lg/k','lg/l'=>'lg/l','lg/u'=>'lg/u','lg50'=>'lg50','lg54'=>'lg54','lge-'=>'lge-','lge/'=>'lge/','lynx'=>'lynx','leno'=>'leno','m1-w'=>'m1-w','m3ga'=>'m3ga','m50/'=>'m50/','maui'=>'maui','mc01'=>'mc01','mc21'=>'mc21','mcca'=>'mcca','medi'=>'medi','meri'=>'meri','mio8'=>'mio8','mioa'=>'mioa','mo01'=>'mo01','mo02'=>'mo02','mode'=>'mode','modo'=>'modo','mot '=>'mot ','mot-'=>'mot-','mt50'=>'mt50','mtp1'=>'mtp1','mtv '=>'mtv ','mate'=>'mate','maxo'=>'maxo','merc'=>'merc','mits'=>'mits','mobi'=>'mobi','motv'=>'motv','mozz'=>'mozz','n100'=>'n100','n101'=>'n101','n102'=>'n102','n202'=>'n202','n203'=>'n203','n300'=>'n300','n302'=>'n302','n500'=>'n500','n502'=>'n502','n505'=>'n505','n700'=>'n700','n701'=>'n701','n710'=>'n710','nec-'=>'nec-','nem-'=>'nem-','newg'=>'newg','neon'=>'neon','netf'=>'netf','noki'=>'noki','nzph'=>'nzph','o2 x'=>'o2 x','o2-x'=>'o2-x','opwv'=>'opwv','owg1'=>'owg1','opti'=>'opti','oran'=>'oran','p800'=>'p800','pand'=>'pand','pg-1'=>'pg-1','pg-2'=>'pg-2','pg-3'=>'pg-3','pg-6'=>'pg-6','pg-8'=>'pg-8','pg-c'=>'pg-c','pg13'=>'pg13','phil'=>'phil','pn-2'=>'pn-2','pt-g'=>'pt-g','palm'=>'palm','pana'=>'pana','pire'=>'pire','pock'=>'pock','pose'=>'pose','psio'=>'psio','qa-a'=>'qa-a','qc-2'=>'qc-2','qc-3'=>'qc-3','qc-5'=>'qc-5','qc-7'=>'qc-7','qc07'=>'qc07','qc12'=>'qc12','qc21'=>'qc21','qc32'=>'qc32','qc60'=>'qc60','qci-'=>'qci-','qwap'=>'qwap','qtek'=>'qtek','r380'=>'r380','r600'=>'r600','raks'=>'raks','rim9'=>'rim9','rove'=>'rove','s55/'=>'s55/','sage'=>'sage','sams'=>'sams','sc01'=>'sc01','sch-'=>'sch-','scp-'=>'scp-','sdk/'=>'sdk/','se47'=>'se47','sec-'=>'sec-','sec0'=>'sec0','sec1'=>'sec1','semc'=>'semc','sgh-'=>'sgh-','shar'=>'shar','sie-'=>'sie-','sk-0'=>'sk-0','sl45'=>'sl45','slid'=>'slid','smb3'=>'smb3','smt5'=>'smt5','sp01'=>'sp01','sph-'=>'sph-','spv '=>'spv ','spv-'=>'spv-','sy01'=>'sy01','samm'=>'samm','sany'=>'sany','sava'=>'sava','scoo'=>'scoo','send'=>'send','siem'=>'siem','smar'=>'smar','smit'=>'smit','soft'=>'soft','sony'=>'sony','t-mo'=>'t-mo','t218'=>'t218','t250'=>'t250','t600'=>'t600','t610'=>'t610','t618'=>'t618','tcl-'=>'tcl-','tdg-'=>'tdg-','telm'=>'telm','tim-'=>'tim-','ts70'=>'ts70','tsm-'=>'tsm-','tsm3'=>'tsm3','tsm5'=>'tsm5','tx-9'=>'tx-9','tagt'=>'tagt','talk'=>'talk','teli'=>'teli','topl'=>'topl','tosh'=>'tosh','up.b'=>'up.b','upg1'=>'upg1','utst'=>'utst','v400'=>'v400','v750'=>'v750','veri'=>'veri','vk-v'=>'vk-v','vk40'=>'vk40','vk50'=>'vk50','vk52'=>'vk52','vk53'=>'vk53','vm40'=>'vm40','vx98'=>'vx98','virg'=>'virg','vite'=>'vite','voda'=>'voda','vulc'=>'vulc','w3c '=>'w3c ','w3c-'=>'w3c-','wapj'=>'wapj','wapp'=>'wapp','wapu'=>'wapu','wapm'=>'wapm','wig '=>'wig ','wapi'=>'wapi','wapr'=>'wapr','wapv'=>'wapv','wapy'=>'wapy','wapa'=>'wapa','waps'=>'waps','wapt'=>'wapt','winc'=>'winc','winw'=>'winw','wonu'=>'wonu','x700'=>'x700','xda2'=>'xda2','xdag'=>'xdag','yas-'=>'yas-','your'=>'your','zte-'=>'zte-','zeto'=>'zeto','acs-'=>'acs-','alav'=>'alav','alca'=>'alca','amoi'=>'amoi','aste'=>'aste','audi'=>'audi','avan'=>'avan','benq'=>'benq','bird'=>'bird','blac'=>'blac','blaz'=>'blaz','brew'=>'brew','brvw'=>'brvw','bumb'=>'bumb','ccwa'=>'ccwa','cell'=>'cell','cldc'=>'cldc','cmd-'=>'cmd-','dang'=>'dang','doco'=>'doco','eml2'=>'eml2','eric'=>'eric','fetc'=>'fetc','hipt'=>'hipt','http'=>'http','ibro'=>'ibro','idea'=>'idea','ikom'=>'ikom','inno'=>'inno','ipaq'=>'ipaq','jbro'=>'jbro','jemu'=>'jemu','java'=>'java','jigs'=>'jigs','kddi'=>'kddi','keji'=>'keji','kyoc'=>'kyoc','kyok'=>'kyok','leno'=>'leno','lg-c'=>'lg-c','lg-d'=>'lg-d','lg-g'=>'lg-g','lge-'=>'lge-','libw'=>'libw','m-cr'=>'m-cr','maui'=>'maui','maxo'=>'maxo','midp'=>'midp','mits'=>'mits','mmef'=>'mmef','mobi'=>'mobi','mot-'=>'mot-','moto'=>'moto','mwbp'=>'mwbp','mywa'=>'mywa','nec-'=>'nec-','newt'=>'newt','nok6'=>'nok6','noki'=>'noki','o2im'=>'o2im','opwv'=>'opwv','palm'=>'palm','pana'=>'pana','pant'=>'pant','pdxg'=>'pdxg','phil'=>'phil','play'=>'play','pluc'=>'pluc','port'=>'port','prox'=>'prox','qtek'=>'qtek','qwap'=>'qwap','rozo'=>'rozo','sage'=>'sage','sama'=>'sama','sams'=>'sams','sany'=>'sany','sch-'=>'sch-','sec-'=>'sec-','send'=>'send','seri'=>'seri','sgh-'=>'sgh-','shar'=>'shar','sie-'=>'sie-','siem'=>'siem','smal'=>'smal','smar'=>'smar','sony'=>'sony','sph-'=>'sph-','symb'=>'symb','t-mo'=>'t-mo','teli'=>'teli','tim-'=>'tim-','tosh'=>'tosh','treo'=>'treo','tsm-'=>'tsm-','upg1'=>'upg1','upsi'=>'upsi','vk-v'=>'vk-v','voda'=>'voda','vx52'=>'vx52','vx53'=>'vx53','vx60'=>'vx60','vx61'=>'vx61','vx70'=>'vx70','vx80'=>'vx80','vx81'=>'vx81','vx83'=>'vx83','vx85'=>'vx85','wap-'=>'wap-','wapa'=>'wapa','wapi'=>'wapi','wapp'=>'wapp','wapr'=>'wapr','webc'=>'webc','whit'=>'whit','winw'=>'winw','wmlb'=>'wmlb','xda-'=>'xda-',))); // check against a list of trimmed user agents to see if we find a match
			  $mobile_browser = true; // set mobile browser to true
			break; // break even though it's the last statement in the switch so there's nothing to break away from but it seems better to include it than exclude it

		} // ends the switch 

		// tell adaptation services (transcoders and proxies) to not alter the content based on user agent as it's already being managed by this script
		header('Cache-Control: no-transform'); // http://mobiforge.com/developing/story/setting-http-headers-advise-transcoding-proxies
		header('Vary: User-Agent, Accept'); // http://mobiforge.com/developing/story/setting-http-headers-advise-transcoding-proxies
		return $mobile_browser;
	}
	
	//CARREGA CLASSES INDIVIDUAIS
	public static function __callStatic($name, $arguments){
		$name = '\libs\utils\\'.$name;
		return call_user_func_array(array($name,'init'),$arguments);
		//return $name::init($arguments);
	}
	
	//CODIFICA UM ARRAY PARA XML
	public static function encodeXML($data, $node = null, $header = false, $att=''){
		$xml = '';
		if(($header)){
			$xml .= '<?xml version="1.0" encoding="utf-8"?>'."\n";	
		}
		$xml .= (!is_null($node) AND !is_int($node)) ? '<'.trim($node.' '.$att).'>'."\n" : '';
			if( array_key_exists(0, $data) ){
				//$xml .= $data[0]."\n";
				//unset($data[0]);
			}
			foreach($data as $key => $val){
				if(is_array($val) || is_object($val)){
					$att = '';
					if(array_key_exists('attr:', $val)){
						foreach($val['attr:'] AS $k=>$v){
							$att .= $k.'="'.$v.'" ';
						}
						unset($val['attr:']);
					}
					if(count($val) == 0){
						$xml .= '<'.trim($key.' '.$att).' />'."\n";
					}
					else{
						$xml .= self::encodeXML($val, $key, false, $att);
					}
					$att = '';
				}
				else{
					//PROBLEMA DO ZERO ESTÁ AQUI NO EMPTY
					$xml .= ($val == '' || is_null($val)) ? '<'.trim($key.''.$att).' />'."\n" : "<$key>" . htmlspecialchars($val) . "</$key>\n";
				}
			}
		$xml .= (!is_null($node) AND !is_int($node)) ? '</'.$node.'>'."\n" : '';
		return $xml;
	}
	
	public static function encodeJSON($data, $node = false, $callBack = false){
		if( is_array($data) ){
			$cIni = ($callBack) ?  $callBack.'('  : null; 
			$cFim = ($callBack) ?  ');'  : null;
			return ( $node ) ? $cIni.'{"'.$node.'":['.json_encode($data).']}'.$cFim : $cIni.json_encode($data).$cFim;
		}
		else{
			return false;
		}
	}
}