import React, { useState } from "react";
import { useParams } from "react-router-dom";
import axios from "axios";
import { Link } from 'react-router-dom';

const Post = () => {
    const { id } = useParams();
    const [post, setPost] = useState(null);

    const fetchPost = async () => {
        try {
            const response = await axios.get(`${process.env.REACT_APP_API_BASE_URL}/post.php/${id}`);
            const post = response.data.data;
            setPost(post);
        } catch (error) {
            console.log(error);
        }
    };


    React.useEffect(() => {
        fetchPost();
    }, []);

    if (!post) {
        return <div>Loading...</div>;
    }

    return (
        <div className="container my-4">
            <div className="d-flex justify-content-left">
                <Link className="btn btn-primary" to="/">Back</Link>
            </div>

            <h1 className="mb-4">{post.title}</h1>
            <p>{post.content}</p>
            <hr />
            <div className="d-flex justify-content-between">
                <div>
                </div>
                <div>
                    <small className="text-muted">
                        publishing date -{post.date}
                    </small>
                </div>
            </div>
        </div>
    );
};

export default Post;