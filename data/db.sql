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

/**
 * The optional column 'on' is used with weekly, monthly, and yearly counters and specifies on which days or dates the calendar should be
 * marked for the counter (have an entry in the History table). If the calendar is not marked on a specified day, a miss (for that day)
 * is inserted into history.
 * 
 * Examples for 'on' column: every 2 weeks on 'mon' and 'tue'.
 *
 * The following are possible values for 'on':
 *  Weekly: 'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'
 *  Monthly:
 *      Date: 1, 2, 3
 *      String: 'first day', 'last day'
 *  Yearly:
 *      Date: '03-15'
 */
create table Counters (
    counterId integer     primary key,
    userId    integer     not null,
    label     varchar(30) not null,
    summary   text        null,               -- Describe this counter
    timeZone  text        null,               -- Timezone of this counter. If null, user's default timezone should be used.
    startDate date        not null,           -- The start date in format 'YYYY-MM-DD' as in '2015-01-15'
    type      text        not null,           -- Possible values: 'daily', 'weekly', 'monthly', and 'yearly'.
    every     integer     null,               -- Every n period since startDate. For example: every 2 days, or every 1 month. Every 1 day means everyday.
    "on"      text        null,               -- See comment above
    active    boolean     not null default 1,
    public    boolean     not null default 1,
    dispOrder integer     not null default 0, -- Display order on the UI
    foreign key(userId) references Users(userId)
);

create table History (
    counterId integer not null,
    date      date    not null,
    miss      boolean not null default 0,
    primary key (counterId, date),
    foreign key(counterId) references Counters(counterId)
);

create table Follows (
    followerId integer not null,
    followeeId integer not null,
    primary key (followerId, followeeId),
    foreign key (followerid) references Users(userId),
    foreign key (followeeId) references Users(userId)
);
