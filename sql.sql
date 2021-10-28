create table users
(
    id       int primary key auto_increment not null,
    idRole   int                            not null,
    name     varchar(50)                    not null,
    lastName varchar(50)                    not null,
    phone    varchar(9)                     not null,
    user     varchar(50)                    not null,
    password varchar(50)                    not null,
    active   tinyint(1) default 1           not null
);

alter table users
    add unique usersU (user, phone);

create table role
(
    id   int primary key auto_increment not null,
    name varchar(50)                    not null
);

insert into role (name)
values ('admin'),
       ('user');

create table incidence
(
    id          int primary key auto_increment      not null,
    idTypeIncid int                                 not null,
    idUser      int                                 not null,
    title       varchar(50)                         not null,
    description text                                not null,
    pdfURL      varchar(350)                        not null,
    dateCreate  timestamp default current_timestamp not null,
    dateModifi  timestamp default current_timestamp not null
);

create table typeIncid
(
    id   int primary key auto_increment not null,
    name varchar(50)                    not null
);

insert into typeIncid (name)
values ('Perdida'),
       ('Robo'),
       ('Vida util');

create table solutInci
(
    id          int primary key auto_increment      not null,
    idIncid     int                                 not null,
    idUser      int                                 not null,
    title       varchar(50)                         not null,
    description text                                not null,
    pdfURL      varchar(350)                        not null,
    dateCreate  timestamp default current_timestamp not null,
    dateModifi  timestamp default current_timestamp not null
);

alter table users
    add constraint idRole_users foreign key (idRole) references role (id) on update restrict on delete restrict;

alter table incidence
    add constraint idTypeIncid_typeIncid foreign key (idTypeIncid) references typeIncid (id) on update restrict on delete restrict;

alter table solutInci
    add constraint idIncid_incid foreign key (idIncid) references incidence (id) on update restrict on delete restrict;

delimiter $
create procedure getUsers(_index int, _limit int)
begin
select u.id as id, r.name as role, u.name as name, u.lastName as lastName, u.user as user
from users u
    inner join role r on u.idRole = r.id
order by id desc
    limit _index,_limit;
end;
$
delimiter ;

delimiter $
create procedure login(_user varchar(50), _password varchar(50))
begin
select u.id as id, r.name as role, u.name as name, u.lastName as lastName, u.user as user
from users u
    inner join role r on u.idRole = r.id
where user like _user
  and password like _password
  and active like 1;
end;
$
delimiter ;

delimiter $
create procedure updaPassw(_id int, _user varchar(50), _oldPassword varchar(50), _newPassword varchar(50))
begin
update users set password = _newPassword where id like _id and user like _user and password like _oldPassword;
end;
$
delimiter ;

delimiter $
create procedure updaRole(_id int, _user varchar(50), _idRole varchar(50))
begin
update users set idRole = _idRole where id like _id and user like _user;
end;
$
delimiter ;

delimiter $
create procedure getRoles()
begin
select * from role;
end;
$
delimiter ;

delimiter $
create procedure getTypeIncid()
begin
select * from typeIncid;
end;
$
delimiter ;

delimiter $
create procedure getIncidUser(_idUser int, _index int, _limit int)
begin
select u.name        as name,
       u.lastName    as lastName,
       u.phone       as phone,
       ti.name       as typeIncid,
       i.title       as title,
       i.description as description,
       i.pdfURL      as pdfURL,
       i.dateCreate  as dateCreate,
       i.dateModifi  as dateModifi
from incidence i
         inner join typeIncid ti on i.idTypeIncid = ti.id
         inner join users u on i.idUser = u.id
where _idUser like _idUser
order by dateModifi desc
    limit _index,_limit;
end;
$
delimiter ;

delimiter $
create procedure getIncidTotal(_idTypeIncid varchar(5),_index int, _limit int)
begin
select count(*) as count
from incidence i
    inner join typeIncid ti on i.idTypeIncid = ti.id
    inner join users u on i.idUser = u.id
where i.idTypeIncid like _idTypeIncid
order by dateModifi desc
    limit _index,_limit;
end;
$
delimiter ;

delimiter $
create procedure getIncid(_idTypeIncid varchar(5),_index int, _limit int)
begin
select u.name        as name,
       u.lastName    as lastName,
       u.phone       as phone,
       ti.name       as typeIncid,
       i.title       as title,
       i.description as description,
       i.pdfURL      as pdfURL,
       i.dateCreate  as dateCreate,
       i.dateModifi  as dateModifi
from incidence i
         inner join typeIncid ti on i.idTypeIncid = ti.id
         inner join users u on i.idUser = u.id
where i.idTypeIncid like _idTypeIncid
    order by dateModifi desc
    limit _index,_limit;
end;
$
delimiter ;

delimiter $
create procedure creaIncid(_idTypeIncid int, _idUser int, _title varchar(50), _description text, _pdfURL varchar(350),
                           _dateCreate timestamp, _dateModifi timestamp)
begin
insert into incidence (idTypeIncid, idUser, title, description, pdfURL, dateCreate, dateModifi)
values (_idTypeIncid, _idUser, _title, _description, _pdfURL, _dateCreate, _dateModifi);
end;
$
delimiter ;

delimiter $
create procedure updaIncid(_id int, _idTypeIncid int, _title varchar(50), _description text, _pdfURL varchar(350),
                           _dateModifi timestamp)
begin
update incidence
set idTypeIncid = _idTypeIncid,
    title       = _title,
    description = _description,
    pdfURL      = _pdfURL,
    dateModifi  = _dateModifi
where id like _id;
end;
$
delimiter ;

delimiter $
create procedure getSolutInci(_index int, _limit int)
begin
select u.name         as name,
       u.lastName     as lastName,
       u.phone        as phone,
       si.title       as title,
       si.description as description,
       si.pdfURL      as pdfUrl,
       si.dateCreate  as dateCreate,
       si.dateModifi  as dateModifi
from solutInci si
         inner join users u on si.idUser = u.id
order by dateModifi desc
    limit _index,_limit;
end;
$
delimiter ;

delimiter $
create procedure creaSolutInci(_idIncid int, _idUser int, _title varchar(50), _description text, _pdfURL varchar(350),
                               _dateCreate timestamp, _dateModifi timestamp)
begin
insert into solutInci (idIncid, idUser, title, description, pdfURL, dateCreate, dateModifi)
values (_idIncid, _idUser, _title, _description, _pdfURL, _dateCreate, _dateModifi);
end;
$
delimiter ;

delimiter $
create procedure updaSolutInci(_id int, _title varchar(50), _description text, _pdfURL varchar(350),
                               _dateModifi timestamp)
begin
update solutInci
set title       =_title,
    description = _description,
    pdfURL      = _pdfURL,
    dateModifi  = _dateModifi
where id like _id;
end;
$
delimiter ;

insert into users (idRole, name, lastName, phone, user, password, active)
values (1, 'rodolfo', 'gavilan', '961266733', 'admin@gmail.com', '3UJBeGmgxKCkHpQdQB4ZtgwkxIHJnU+e7br24M2XCUE=', 1)
