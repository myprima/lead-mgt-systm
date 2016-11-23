<?php
function encrypt($data,$key,$iv='') {
    if(!extension_loaded('mcrypt')) {
	return array('iv'=>'i','data'=>$data);
    }

    srand();
    if(!$iv) {
	$iv=mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_GOST,MCRYPT_MODE_CFB),MCRYPT_RAND);
    }
    $encrypted=mcrypt_encrypt(MCRYPT_GOST,$key,$data,MCRYPT_MODE_CFB,$iv);
    return array('iv'=>$iv,'data'=>$encrypted);
}

function decrypt($data,$key,$iv) {
    if(!extension_loaded('mcrypt')) {
	return $data;
    }
    
    return mcrypt_decrypt(MCRYPT_GOST,$key,$data,MCRYPT_MODE_CFB,$iv);
}
?>
