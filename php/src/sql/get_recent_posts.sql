SELECT
    posts.id AS post_id,
    users.id AS user_id,
    users.username,
    posts.title,
    posts.content,
    posts.created_at,
    COUNT(likes.id) AS like_count,
    MAX(user_likes.id IS NOT NULL) AS liked_by_user
FROM
    posts
JOIN users ON posts.user_id = users.id
LEFT JOIN likes ON posts.id = likes.post_id
LEFT JOIN likes AS user_likes
ON
    posts.id = user_likes.post_id AND user_likes.user_id = :userId
GROUP BY
    posts.id
ORDER BY
    created_at
DESC
LIMIT 10
