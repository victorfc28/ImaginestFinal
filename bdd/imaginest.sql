create database imaginest character set utf8 collate utf8_bin;
use imaginest;

create table if not exists users(
	iduser int auto_increment primary key,
    mail varchar(40) unique,
    username varchar(16) unique,
    passHash varchar(60),
    userFirstName varchar(60),
    userLastName varchar(120),
    creationDate datetime,
    lastSignIn datetime,
    removeDate datetime,
    active tinyint(1),
    activationDate datetime,
    activationCode char(64),
    resetPass tinyint(1),
    resetPassExpiry datetime,
    resetPassCode char(64)
)Engine=InnoDB;

create table if not exists photos(
    photoID int auto_increment primary key,
    publishDate datetime,
    photoText text,
    likes int,
    dislikes int,
    url text unique,
    iduser int,
    FOREIGN KEY (iduser) REFERENCES users(iduser)
)Engine=InnoDB;

create table if not exists tags(
    tagName varchar(255) primary key
)Engine=InnoDB;

create table if not exists te(
    photoID int,
    tagName varchar(255),
    primary key (tagName,photoID),
    constraint fk_Te_Photos foreign key (photoID) references photos(photoID) 
		on delete restrict
        on update restrict,
	constraint fk_Te_Tags foreign key (tagName) references tags(tagName) 
		on delete restrict
        on update restrict
)Engine=InnoDB;

create table if not exists Fa_like(
    likea int,
    dislikea int,
    photoID int,
    iduser int,
    primary key (photoID,iduser),
    constraint fk_Fa_like_Photos foreign key (photoID) references photos(photoID) 
		on delete restrict
        on update restrict,
	constraint fk_Fa_like_Users foreign key (iduser) references users(iduser) 
		on delete restrict
        on update restrict
)Engine=InnoDB;