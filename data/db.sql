/**
 * Sqlite 3 database
 */

create table Users (
    userId   integer primary key,          -- auto-increment by default
    userName varchar(30) not null unique,  -- unique, max_len = 30
    email    text not null,
    phash    text not null, --password hash with salt appended
    joinDate text not null,
    timeZone text not null,
    authKey  text not null  --Yii Framework authentication key
);

create table TempUsers (
    userId   integer primary key,
    userName varchar(30) not null unique,
    email    text not null,
    phash    text not null, --password hash with salt appended
    joinDate text not null,
    timeZone text not null,
    code     text not null  -- Verification code
);

create table Counters (
    counterId integer primary key,
    userId    integer not null,
    label     varchar(30) not null,
    startDate text not null,
    summary   text null,
    public    integer not null default 1,
    active    integer not null default 1,
    foreign key(userId) references Users(userId)
);

create table History (
    counterId integer not null,
    startDate text not null,
    endDate   text not null,
    primary key (counterId, startDate, endDate),
    foreign key(counterId) references Counters(counterId)
);
