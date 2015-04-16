/**
 * Sqlite 3 database
 */

create table Users (
    userId   integer     primary key, -- auto-increment by default
    userName varchar(30) null unique, -- unique, max_len = 30
    forename text        not null,
    surname  text        not null,
    email    text        not null,
    fbId     integer     null,
    phash    text        null,        -- password hash with salt appended
    joinDate text        not null,
    location text        null,        -- Name of the location, i.e. Auburn, Alabama
    timeZone text        not null,    -- Name of the timezone, i.e. America/Chicago
    picture  text        null,        -- Url of profile picture
    authKey  text        not null     -- Yii Framework authentication key
);

/**
 * Mirrors Users table
 */
create table TempUsers (
    userId   integer     primary key,
    userName varchar(30) null unique,
    forename text        not null,
    surname  text        not null,
    email    text        not null,
    fbId     integer     null,
    phash    text        not null,
    joinDate text        not null,
    location text        null,
    timeZone text        not null,
    code     text        not null     -- Verification code
);

create table Counters (
    counterId integer     primary key,
    userId    integer     not null,
    label     varchar(30) not null,
    startDate text        not null,
    summary   text        null,               -- Describe this counter
    timeZone  text        null,               -- Timezone of this counter. If null, user's default timezone should be used.
    longest   integer     not null default 0, -- Longest day streak
    public    integer     not null default 1,
    active    integer     not null default 1,
    foreign key(userId) references Users(userId)
);

create table History (
    counterId integer not null,
    startDate text not null,
    endDate   text not null,
    primary key (counterId, startDate, endDate),
    foreign key(counterId) references Counters(counterId)
);
