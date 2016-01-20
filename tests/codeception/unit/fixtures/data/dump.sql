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

INSERT INTO Users VALUES(
    /*userId*/   1,
    /*userName*/ 'Jeff',
    /*foreName*/ 'Jeff',
    /*surname*/  'Wang',
    /*gender*/   'male',
    /*email*/    'me@test.com',
    /*fbId*/     null,
    /*phash*/    '$2y$10$KO5HdM03CdSOrpfBGJEKNedmvN1yLGNEE9w6AK73O.0QdEg6qlPAi',
    /*joinDate*/ '2014-08-22 19:50:49',
    /*location*/ null,
    /*timeZone*/ 'America/Chicago',
    /*picture*/  null,
    /*authKey*/  'weMW5GI3aQbaV-IgsdE3VS8Wish83uFI'
);

INSERT INTO Users VALUES(
    /*userId*/   2,
    /*userName*/ null,
    /*foreName*/ 'Eugenie',
    /*surname*/  'Bouchard',
    /*gender*/   'female',
    /*email*/    'a@a.com',
    /*fbId*/     null,
    /*phash*/    '$2y$10$KO5HdM03CdSOrpfBGJEKNedmvN1yLGNEE9w6AK73O.0QdEg6qlPAi',
    /*joinDate*/ '2014-08-22 19:50:49',
    /*location*/ null,
    /*timeZone*/ 'America/Chicago',
    /*picture*/  null,
    /*authKey*/  'weMW5GI3aQbaV-IgsdE3VS8Wish83uFI'
);

INSERT INTO Users VALUES(
    /*userId*/   3,
    /*userName*/ null,
    /*foreName*/ 'Nei',
    /*surname*/  'Nishikori',
    /*gender*/   'male',
    /*email*/    'b@b.com',
    /*fbId*/     null,
    /*phash*/    '$2y$10$KO5HdM03CdSOrpfBGJEKNedmvN1yLGNEE9w6AK73O.0QdEg6qlPAi',
    /*joinDate*/ '2014-08-22 19:50:49',
    /*location*/ null,
    /*timeZone*/ 'America/Chicago',
    /*picture*/  null,
    /*authKey*/  'weMW5GI3aQbaV-IgsdE3VS8Wish83uFI'
);

INSERT INTO Users VALUES(
    /*userId*/   4,
    /*userName*/ null,
    /*foreName*/ 'Ana',
    /*surname*/  'Ivanovic',
    /*gender*/   'female',
    /*email*/    'c@c.com',
    /*fbId*/     null,
    /*phash*/    '$2y$10$KO5HdM03CdSOrpfBGJEKNedmvN1yLGNEE9w6AK73O.0QdEg6qlPAi',
    /*joinDate*/ '2014-08-22 19:50:49',
    /*location*/ null,
    /*timeZone*/ 'America/Chicago',
    /*picture*/  null,
    /*authKey*/  'weMW5GI3aQbaV-IgsdE3VS8Wish83uFI'
);

INSERT INTO Users VALUES(
    /*userId*/   5,
    /*userName*/ null,
    /*foreName*/ 'Roger',
    /*surname*/  'Federer',
    /*gender*/   'male',
    /*email*/    'd@d.com',
    /*fbId*/     null,
    /*phash*/    '$2y$10$KO5HdM03CdSOrpfBGJEKNedmvN1yLGNEE9w6AK73O.0QdEg6qlPAi',
    /*joinDate*/ '2014-08-22 19:50:49',
    /*location*/ null,
    /*timeZone*/ 'America/Chicago',
    /*picture*/  null,
    /*authKey*/  'weMW5GI3aQbaV-IgsdE3VS8Wish83uFI'
);

INSERT INTO Users VALUES(
    /*userId*/   6,
    /*userName*/ null,
    /*foreName*/ 'Michael',
    /*surname*/  'Jordan',
    /*gender*/   'male',
    /*email*/    'e@e.com',
    /*fbId*/     null,
    /*phash*/    '$2y$10$KO5HdM03CdSOrpfBGJEKNedmvN1yLGNEE9w6AK73O.0QdEg6qlPAi',
    /*joinDate*/ '2014-08-22 19:50:49',
    /*location*/ null,
    /*timeZone*/ 'America/Chicago',
    /*picture*/  null,
    /*authKey*/  'weMW5GI3aQbaV-IgsdE3VS8Wish83uFI'
);

INSERT INTO Users VALUES(
    /*userId*/   7,
    /*userName*/ null,
    /*foreName*/ 'Ivy',
    /*surname*/  'Chung',
    /*gender*/   'female',
    /*email*/    'f@f.com',
    /*fbId*/     null,
    /*phash*/    '$2y$10$KO5HdM03CdSOrpfBGJEKNedmvN1yLGNEE9w6AK73O.0QdEg6qlPAi',
    /*joinDate*/ '2014-08-22 19:50:49',
    /*location*/ null,
    /*timeZone*/ 'America/Chicago',
    /*picture*/  null,
    /*authKey*/  'weMW5GI3aQbaV-IgsdE3VS8Wish83uFI'
);




INSERT INTO Counters VALUES(
    /*counterId*/ 1,
    /*userId*/    1,
    /*label*/     'Coding Daily',
    /*summary*/   'Test functionality of this app.',
    /*timeZone*/  'America/Chicago',
    /*public*/    1,
    /*dispOrder*/ 0
);

INSERT INTO Counters VALUES(
    /*counterId*/ 2,
    /*userId*/    1,
    /*label*/     'Running',
    /*summary*/   'Run for at least 20 minutes every other day',
    /*timeZone*/  'America/Chicago',
    /*public*/    1,
    /*dispOrder*/ 1
);

INSERT INTO Counters VALUES(
    /*counterId*/ 3,
    /*userId*/    1,
    /*label*/     'One Month',
    /*summary*/   '',
    /*timeZone*/  'America/Chicago',
    /*public*/    1,
    /*dispOrder*/ 2
);

INSERT INTO Counters VALUES(
    /*counterId*/ 4,
    /*userId*/    1,
    /*label*/     'No History',
    /*summary*/   'This counter has no entry in the History table.',
    /*timeZone*/  'America/Chicago',
    /*public*/    1,
    /*dispOrder*/ 3
);

/*
History Date Range Test cases
    1. Boundary cases
        1. Start of the year: Jan 01 - Jan 02
        2. End of the year:   Dec 30 - Dec 31
        3. Cross year:        Dec 30 - Jan 02
    2. Normal cases
        1. Degenerate (0 day count): Jan 03 - Jan 03
            In general, should we record degnerates?
        2. No end date
    3. Illegal Cases
        1. Start and end date in the future
        2. Only end date in the future
*/

/**
 * Boundary Cases
 */

-- Jan 1 - Jan 2 (1 day)
insert into History values(
    1,
    date('now', '-1 month', 'start of month'),
    date('now', '-1 month', 'start of month', '+1 day')
);

/** 
 * Normal Cases
 */

-- 0 days
insert into History values(
    1,
    date('now', '-1 month', 'start of month', '+1 day'),
    date('now', '-1 month', 'start of month', '+1 day') 
);

-- 5 days
insert into History values(
    1,
    date('now', '-1 month', 'start of month', '+2 day'),
    date('now', '-1 month', 'start of month', '+7 day')
);

-- 7 days
insert into History values(
    1,
    date('now', '-1 month', 'start of month', '+9 day'),
    date('now', '-1 month', 'start of month', '+16 day')
);

-- 8 days
insert into History values(
    1,
    date('now', '-1 month', 'start of month', '+18 day'),
    date('now', '-1 month', 'start of month', '+26 day')
);

-- 11 days
insert into History values(
    2,
    date('now', '-1 month', 'start of month'),
    date('now', '-1 month', 'start of month', '+11 day')
);

-- Current month, current count
insert into History values(
    2,
    date('now', '-3 days'),
    null
);

-- No end date; current count (5 days)
insert into History values(
    1,
    date('now', '-5 day'),
    null
);

insert into History values (
    3,
    date('now', '-1 months'),
    null
);

insert into Follows values(1, 2);
insert into Follows values(1, 3);
insert into Follows values(1, 4);
insert into Follows values(1, 5);
insert into Follows values(2, 1);
insert into Follows values(3, 4);
insert into Follows values(6, 1);