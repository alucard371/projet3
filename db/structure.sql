drop table if exists comment;
drop table if exists user;
drop table if exists article;

create table article (
    art_id integer not null primary key auto_increment,
    art_title varchar(100) not null,
    art_content varchar(2000) not null
) engine=innodb character set utf8 collate utf8_unicode_ci;

create table user (
    usr_id integer not null primary key auto_increment,
    usr_name varchar(50) not null,
    usr_password varchar(88) not null,
    usr_salt varchar(23) not null,
    usr_role varchar(50) not null
) engine=innodb character set utf8 collate utf8_unicode_ci;

create table comment (
    com_id integer not null primary key auto_increment,
    com_content varchar(500) not null,
    art_id integer not null,
    usr_id integer not null,
    par_id integer not NULL,
    published BOOLEAN DEFAULT '1' NOT NULL ,
    constraint fk_com_art foreign key(art_id) references article(art_id),
    constraint fk_com_usr foreign key(usr_id) references user(usr_id)
) engine=innodb character set utf8 collate utf8_unicode_ci;