create database hw1;
drop database hw1;
use hw1;
drop table users;
create table users
(
	id int auto_increment primary key,
    name varchar(30),
    surname varchar(30),
	username varchar(30) unique,
    password varchar(30),
    email_address varchar(50) unique,
    date_birth date,
    sign_up_date date,
    photo_path varchar(60)
);

create table games
(
	id int primary key not null,
    cover varchar(255) not null,
    releaseDate int not null,
    name varchar(255) not null,
    genres varchar(255),
    platforms varchar(255)
);
create table likes
(
	idUser int,
    idGame int,
    primary key (idUser, idGame),
    foreign key (idUser) references users(id),
    foreign key (idGame) references games(id)
);

