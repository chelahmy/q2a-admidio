<?php
// q2a-admidio-helper.php
// Question2Answer Single Sign-on with Admidio Helper Functions
// Note: These functions run within Q2A while both Q2A and Admidio
//       share the same database, separated by application prefixes.
// By Abdullah Daud, chelahmy@gmail.com
// 17 December 2020

$admidio_table_prefix = "adm";

function adm_tbl($table_name) {
	global $admidio_table_prefix;
	if ($admidio_table_prefix == null || strlen($admidio_table_prefix) <= 0)
		return $table_name;
	return $admidio_table_prefix . "_" . $table_name;
}

function get_admidio_session_id() {
    foreach ($_COOKIE as $key => $val) {
        if (strpos($key, "ADMIDIO_") === 0) { // starts with
            if (strpos($key, "_SESSION_ID") === (strlen($key) - 11)) // ends with
                return $val;
        }
    }
    return false;
}

function get_admidio_user_id_from_session_id($session_id) {
    $result = qa_db_read_one_assoc(qa_db_query_sub(
        'SELECT ses_usr_id FROM ' . adm_tbl('sessions') . ' WHERE ses_session_id=$',
        $session_id 
        ),
        true // allow empty result
    );
    if (is_array($result) && isset($result['ses_usr_id'])) {
        return intval($result['ses_usr_id']);
    }
    return 0;
}

function get_admidio_user_name($user_id) {
    $result = qa_db_read_one_assoc(qa_db_query_sub(
        'SELECT usr_login_name FROM ' . adm_tbl('users') . ' WHERE usr_id=#',
        $user_id 
        ),
        true // allow empty result
    );
    if (is_array($result) && isset($result['usr_login_name'])) {
        return $result['usr_login_name'];
    }
    return false;
}

function get_admidio_user_names($user_ids) {
	$uid2uname = array();
	if (count($user_ids)) {
		$esc_uids = array();
		foreach ($user_ids as $uid)
			$esc_uids[] = intval($uid);
		$results = qa_db_read_all_assoc(qa_db_query_raw(
			'SELECT usr_login_name, usr_id FROM ' . adm_tbl('users') . ' WHERE usr_id IN (' . implode(',', $esc_uids) . ')'
		));
		foreach ($results as $result)
			$uid2uname[$result['usr_id']] = $result['usr_login_name'];
	}
	return $uid2uname;
}

function get_admidio_user_id_from_user_name($user_name) {
    $result = qa_db_read_one_assoc(qa_db_query_sub(
        'SELECT usr_id FROM ' . adm_tbl('users') . ' WHERE usr_login_name=$',
        $user_name 
        ),
        true // allow empty result
    );
    if (is_array($result) && isset($result['usr_id'])) {
        return intval($result['usr_id']);
    }
    return 0;
}

function get_admidio_user_ids_from_user_names($user_names) {
	$uname2uid = array();
	if (count($user_names)) {
		$esc_unames = array();
		foreach ($user_names as $uname)
			$esc_unames[] = "'" . qa_db_escape_string($uname) . "'";
		$results = qa_db_read_all_assoc(qa_db_query_raw(
			'SELECT usr_login_name, usr_id FROM ' . adm_tbl('users') . ' WHERE usr_login_name IN (' . implode(',', $esc_unames) . ')'
		));
		foreach ($results as $result)
			$uname2uid[$result['usr_login_name']] = $result['usr_id'];
	}
	return $uname2uid;
}

function get_admidio_user_field_id($user_field_name) {
    $result = qa_db_read_one_assoc(qa_db_query_sub(
        'SELECT usf_id FROM ' . adm_tbl('user_fields') . ' WHERE usf_name=$',
        $user_field_name 
        ),
        true // allow empty result
    );
    if (is_array($result) && isset($result['usf_id'])) {
        return intval($result['usf_id']);
    }
    return 0;
}

function get_admidio_user_email($user_id) {
	$usf_id = get_admidio_user_field_id('SYS_EMAIL');
	if ($usf_id <= 0)
		return false;
    $result = qa_db_read_one_assoc(qa_db_query_sub(
        'SELECT usd_value FROM ' . adm_tbl('user_data') . ' WHERE usd_usr_id=# AND usd_usf_id=#',
        $user_id,
        $usf_id
        ),
        true // allow empty result
    );
    if (is_array($result) && isset($result['usd_value'])) {
        return $result['usd_value'];
    }
    return false;
}

function get_admidio_user_photo($user_id) {
    $result = qa_db_read_one_assoc(qa_db_query_sub(
        'SELECT usr_photo FROM ' . adm_tbl('users') . ' WHERE usr_id=#',
        $user_id 
        ),
        true // allow empty result
    );
    if (is_array($result) && isset($result['usr_photo'])) {
        return $result['usr_photo'];
    }
    return false;
}

?>
