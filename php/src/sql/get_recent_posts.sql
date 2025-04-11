SELECT
    posts.id,
    users.username,
    posts.title,
    posts.content,
    posts.created_at,
    COUNT(likes.id) AS like_count
FROM
    posts
JOIN users ON posts.user_id = users.id
LEFT JOIN likes ON posts.id = likes.post_id
GROUP BY
    posts.id
ORDER BY
    created_at
DESC
LIMIT 10
