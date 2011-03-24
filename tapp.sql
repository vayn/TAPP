BEGIN TRANSACTION;
CREATE TABLE users (
    id INTEGER NOT NULL PRIMARY KEY,
    user VARCHAR(20) NOT NULL,
    password VARCHAR(32) NOT NULL
)
;
CREATE TABLE setting (
    id INTEGER NOT NULL PRIMARY KEY,
    uid INTEGER NOT NULL REFERENCES "users" ("id"),
    twitter VARCHAR(30) NOT NULL,
    cron INTEGER NOT NULL DEFAULT 0,
    cache_time INTEGER NOT NULL,
    update_time TIMESTAMP,
    amount INTEGER NOT NULL,
    type VARCHAR(10) NOT NULL,
    reply VARCHAR(10) NOT NULL,
    latest TEXT NOT NULL
)
;
CREATE TABLE tweets (
    id INTEGER NOT NULL PRIMARY KEY,
    uid INTEGER NOT NULL REFERENCES "users" ("id"),
    text TEXT NOT NULL,
    id_str VARCHAR(50) NOT NULL
)
;
INSERT INTO users VALUES(1, 'hal', '7f50914da1f9c70cf6359be4534e6ab0f7e1cbde');
INSERT INTO setting VALUES(1, 1, 'twitter', 0, 1800, '', 1, 'json', 'no', '');
COMMIT;
