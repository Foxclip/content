SELECT
    posts.id,
    users.username,
    posts.title,
    posts.content,
    posts.created_at,
    COUNT(likes.id) AS like_count,
    CASE WHEN EXISTS(
        SELECT
            1
        FROM
            likes AS likes2
        WHERE
            likes2.post_id = posts.id AND likes2.user_id = :userId
    ) THEN TRUE ELSE FALSE
	END AS liked_by_user
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
