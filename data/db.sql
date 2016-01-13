/**
 * Sqlite 3 database
 */

create table Users (
    userId   integer     primary key, -- auto-increment by default
    userName varchar(30) null unique, -- unique, max_len = 30
    forename text        not null,
    surname  text        not null,
    gender   text        not null,
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
    gender   text        not null,
    email    text        not null,
    fbId     integer     null,
    phash    text        not null,
    joinDate text        not null,
    location text        null,
    timeZone text        not null,
    code     text        not null     -- Verification code
);

create table Counters (
    counterId integer     primary key,        -- The primary key of this counter.
    userId    integer     not null,           -- The user id of the owner of this counter.
    label     varchar(30) not null,           -- The name of this counter.
    summary   text        null,               -- Describe this counter.
    timeZone  text        null,               -- Timezone of this counter. If null, user's default timezone should be used.
    public    boolean     not null default 1,
    dispOrder integer     not null default 0, -- Display order on the UI.
    foreign key(userId) references Users(userId)
);

create table History (
    counterId integer not null,
    startDate date    not null,  -- The start date in format 'YYYY-MM-DD' as in '2015-01-15'
    endDate   date    null,      -- The end date; if null, this is the current count; same format as start date.
    primary key (counterId, startDate, endDate),
    foreign key(counterId) references Counters(counterId)
);

create table Follows (
    followerId integer not null,
    followeeId integer not null,
    primary key (followerId, followeeId),
    foreign key (followerid) references Users(userId),
    foreign key (followeeId) references Users(userId)
);
