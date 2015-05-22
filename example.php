<?php
/**
 * Created by PhpStorm.
 * User: Morteza
 * Date: 5/16/2015
 * Time: 19:25
 */

include_once 'entity/Person.php';


function load_user($username){
    $u = new Person($username);
    var_dump($u);
}
function save_user($username){
    $u = new Person();
    $u->username = $username;
    $u->age = 50;
    $u->name = 'ned';
    $u->family = 'stark';
    $u->Save();
}
function delete_user($username){
    $u = new Person($username);
    $u->Delete();
}
function update_user($username){
    $u = new Person($username);
    $u->age = 50;
    $u->name = 'Alex';
    $u->family = 'stef';
    $u->Update();

}
function exists($username){

}

//save_user('test3');
//delete_user('test2');
//update_user('test3');
load_user('test3');



//function fff(Users $jjj){
//    $jjj->
//}

