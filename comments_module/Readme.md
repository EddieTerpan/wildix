### 2 ways to make tree struct in table
I chose the second Materialized Path - because max() working faster
and Nested Set looks confusing

### 1. Recursion
````
CREATE TABLE comments (
comment_id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT,
post_id INT,
comment_text TEXT,
created_at TIMESTAMP,
parent_comment_id INT
);

CREATE INDEX idx_created_at ON comments (created_at);
CREATE INDEX idx_parent_comment_id ON comments (parent_comment_id);
CREATE INDEX idx_parent_created_at ON comments (parent_comment_id, created_at);  WITH RECURSIVE CommentTree AS (
SELECT

comment_id,
user_id,
post_id,
comment_text,
created_at,
parent_comment_id
FROM comments
WHERE parent_comment_id IS NULL
ORDER BY created_at DESC
LIMIT 1
UNION ALL
SELECT
c.comment_id,
c.user_id,
c.post_id,
c.comment_text,
c.created_at,
c.parent_comment_id
FROM CommentTree AS p
JOIN comments AS c ON p.comment_id = c.parent_comment_id
)
SELECT * FROM CommentTree
ORDER BY created_at DESC;
````

| comment_id | user_id | post_id | comment_text | created_at         | parent_comment_id |
|------------|---------|---------|--------------|-------------------|-------------------|
| 1          | 101     | 1       | Comment 1    | 2023-11-01 10:00  | NULL              |
| 2          | 102     | 1       | Comment 2    | 2023-11-01 11:00  | 1                 |
| 3          | 103     | 1       | Comment 3    | 2023-11-01 11:30  | 1                 |
| 4          | 104     | 1       | Comment 4    | 2023-11-01 11:45  | 2                 |
| 5          | 105     | 1       | Comment 5    | 2023-11-01 12:00  | NULL              |
| 6          | 106     | 1       | Comment 6    | 2023-11-01 12:30  | 5                 |
| 7          | 107     | 1       | Comment 7    | 2023-11-01 13:00  | 6                 |


### 2. Materialized Path

```SELECT
c.comment_id,
c.user_id,
c.post_id,
c.comment_text,
c.created_at,
c.path,
IFNULL(latest.latest_created_at, c.created_at) AS latest_created_at
FROM comments c
LEFT JOIN (
SELECT
parent_comment_id,
MAX(created_at) AS latest_created_at
FROM comments
GROUP BY parent_comment_id
) AS latest ON c.comment_id = latest.parent_comment_id
ORDER BY IFNULL(latest.latest_created_at, c.created_at) DESC, c.path;
```

| comment_id | user_id | post_id | comment_text | created_at         | parent_comment_id | path    | latest_created_at  |
|------------|---------|---------|--------------|-------------------|-------------------|---------|-------------------|
| 4          | 4       | 1       | Comment 4    | 2023-01-01 10:45  | NULL              | 4       | 2023-01-01 11:15  |
| 5          | 5       | 1       | Comment 5    | 2023-01-01 11:00  | 4                 | 4/5     | 2023-01-01 11:15  |
| 6          | 6       | 1       | Comment 6    | 2023-01-01 11:15  | 4                 | 4/6     | 2023-01-01 11:15  |
| 1          | 1       | 1       | Comment 1    | 2023-01-01 10:00  | NULL              | 1       | 2023-01-01 10:30  |
| 2          | 2       | 1       | Comment 2    | 2023-01-01 10:15  | 1                 | 1/2     | 2023-01-01 10:30  |
| 3          | 3       | 1       | Comment 3    | 2023-01-01 10:30  | 1                 | 1/3     | 2023-01-01 10:30  |