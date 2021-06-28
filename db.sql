drop database if exists phpProject;
create database phpProject;
use phpProject;
create table contact(
	fullname varchar(255) not null,
    email varchar(255) not null,
    message varchar(400) not null,
	CONSTRAINT contact CHECK (email like '%@gmail.com')
);
create table appointment(
	fullname varchar(255) not null,
    email varchar(255) not null,
    phoneNumber char(20) ,
    appointment_at datetime not null,
    message varchar(400) ,
    CONSTRAINT appointment CHECK (email like '%@gmail.com')
);
insert into contact values('sadfasdf','sadgadsg@gmail.com','adsgdfg');
insert into appointment values('thao','thao@gmail.com',NULL,'2021-06-14 14:00:05',NULL);