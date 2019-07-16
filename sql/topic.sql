create database codeigniter character set urf8 collate utf8_general_ci;

create table topic(
    id int(11) not null auto_increment,
 	title varchar(255) not null,
 	description text null,
 	created datetime not null,
    primary key (id)
);