session_start();
if($_POST['start']){
    if(empty($_SESSION['email'])){
        $result['reqest'] = 'not session';
    
    }
}
include_once($_SERVER['DOCUMENT_ROOT'] . '/RS/user.php');
$query = new DB;
if(!empty($_REQUEST['email'])){
    mkdir($_REQUEST['email'], 0775);
    $_SESSION['email'] = $_REQUEST['email'];
    $status = 1;
    $set = $query->setStatus($_SESSION['email'], $status);
    $get = $query->getStatus($_SESSION['email']);
        if($get['EMAIL'] == $_SESSION['email']){
            $result['itog'] = 'Пользователь существует.';
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/RS/rs_1/ajax/' . $_SESSION['email'] . '/';
            $arr_dir = scandir($dir);
            $select_arr = array_slice($arr_dir, 2);
            $result['sel'] .= '<select name="req_rs_file" id="req_rs_file">';
            foreach($select_arr as $value) {
                $result['sel'] .= '<option value="' . $value . '">' . $value . '</option>';
            }
            $result['sel'] .= '</select>';
        }
        if($get['COUNT'] != 0){
            $result['btn'] = 'btn stop';
        }
    
}
    

if (!empty($_FILES) && $_FILES['img']['error'] == 0) { // Проверяем, загрузил ли пользователь файл
    
    
    $dir = $_SERVER['DOCUMENT_ROOT'] . '/RS/rs_1/ajax/' . $_SESSION['email'] . '/';
    $new_name = '1-file.txt';
    $count_file = 0;
     if($count_file != 0){
        $result['button'] = 'button stop';
    }
   
    $setCount = $query->setCount($_SESSION['email'], $count_file);
    
    $destiation_dir = $_SERVER['DOCUMENT_ROOT'] . '/RS/rs_1/ajax/' . $_SESSION['email'] . '/' . $new_name; // Директория для размещения файла
    
    move_uploaded_file($_FILES['img']['tmp_name'], $destiation_dir); // Перемещаем файл в желаемую директорию

        $result['result'] = 'Файл успешно загружен.';
        $arr_dir = scandir($dir);
        $select_arr = array_slice($arr_dir, 2);
        $result['select'] .= '<select name="req_rs_file" id="req_rs_file">';
        foreach($select_arr as $value) {
            $result['select'] .= '<option value="' . $value . '">' . $value . '</option>';
        }
        $result['select'] .= '</select>';
} else {
        $result['result'] = 'Ошибка загрузки файла';
}
 

if(!empty($_POST['rs'])){
    if(!empty($_POST['f_rs'])){
        $select_res = $_POST['f_rs'];
        $dir = $_SERVER['DOCUMENT_ROOT'] . '/RS/rs_1/ajax/' . $_SESSION['email'] . '/';
        $arr_dir = scandir($dir);
        $select_arr = array_slice($arr_dir, 2);
        $massive = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/RS/rs_1/ajax/' . $_SESSION['email'] . '/' . $select_res);
    }else{
        $massive = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/RS/rs_1/ajax/' . $_SESSION['email'] . '/' . $select_res);
    }


    // echo ($massive);
    $massive_data = explode("\n", "$massive");
    foreach ($massive_data as $key => $value) {
        // if (mb_stripos($value, 'rs', 0, "UTF-8") === 0) {
        //     $space = mb_strpos($value, '	', 2, "UTF-8");
        //     $rs = substr($value, 0, $space);
        //     $gt = substr($value, strlen($value) - 2);
        //     $massive_arr[$rs] = $gt;
        // }
        preg_match('/^([a-z]+\d+)\s+(\d+|[a-z]+)\s+\d+\s+(--|[a-z]+)/iu', $value, $matches);
            // if(!$matches){
            //     preg_match('/^([a-z]+\d+)\s+\d+\s+(\d+|[a-z]+)\s+\D+\s+\D+\s+\D\s+\D\s+\D\s+(--|[a-z]+)/iu', $value, $matches);
            // }
        $massive_arr[$matches[1]] = $matches[3];
    }
    

    if (!empty($massive_arr[$_POST['rs']]) && $massive_arr[$_POST['rs']] != '--') {
        echo $massive_arr[$_POST['rs']];
    } else {
        echo 'Нет ответа';
    }
}else{
    print(json_encode($result));
}
