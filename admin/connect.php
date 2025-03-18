<?php
//Ket noi database qlbh1
$connect = mysqli_connect('localhost','root','','webbanbanh');
//Neu ket noi bi loi thi xuat bao loi va thoat
if (!$connect){
    die("Kết nối thất bại!" . mysqli_connect_error());
}
?>