create database if not exists projet3 character set utf8 collate utf8_unicode_ci;
use projet3;

grant all privileges on projet3.* to 'projet3_user'@'localhost' identified by 'secret';