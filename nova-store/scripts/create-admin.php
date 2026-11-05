<?php
declare(strict_types=1);
if(PHP_SAPI!=='cli'){http_response_code(404);exit("Not found\n");}
require_once dirname(__DIR__).'/config/database.php';
$email=mb_strtolower(trim((string)readline('Admin email: ')));
if(!filter_var($email,FILTER_VALIDATE_EMAIL)){fwrite(STDERR,"Invalid email\n");exit(1);}
$name=trim((string)readline('Admin name: '));
$password=(string)readline('Admin password (12+ characters): ');
if($name===''||strlen($password)<12){fwrite(STDERR,"Name required; password must be 12+ characters\n");exit(1);}
try{$stmt=db()->prepare("INSERT INTO users(name,email,password_hash,role) VALUES(?,?,?,'admin')");$stmt->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT)]);echo "Admin account created.\n";}
catch(PDOException $e){fwrite(STDERR,"Could not create administrator. Email may already exist.\n");exit(1);}
