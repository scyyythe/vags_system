import { useContext, useEffect, useState } from "react";
import { AppContext } from "../../context/AppContext";
import { useNavigate } from "react-router-dom";
import Layout from "../../components/layouts/Layout";

// Define the Post interface
interface Post {
  id: number;
  title: string;
  user: {
    name: string;
  };
  created_at: string;
  category: string;
  image: string;
}

export default function Dashboard() {
  const { user } = useContext(AppContext);
  const [posts, setPosts] = useState<Post[]>([]);
  const navigate = useNavigate();

  useEffect(() => {
    if (user) {
      navigate("/dashboard");
    }
  }, [user, navigate]);

  async function getPosts() {
    const res = await fetch("/api/posts");
    const data = await res.json();

    if (res.ok) {
      setPosts(data);
    }
    // console.log(data);
  }

  useEffect(() => {
    getPosts();
  }, []);

  return (
    <Layout>
      <p className="text-xl font-semibold mb-4">Hello Works</p>
      {posts.length > 0 ? (
        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
          {posts.map((post) => {
            return (
              <div
                key={post.id}
                className="bg-white shadow-lg rounded-lg overflow-hidden"
              >
                {/* Image Section */}
                <div>
                  <img
                    src={`/storage/${post.image}`}
                    alt={post.title}
                    className="w-full h-64 object-cover"
                  />
                </div>

                {/* Post Content Section */}
                <div className="p-4">
                  <div className="flex items-center mb-2">
                    <div className="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center mr-4">
                      {/* Profile Image or Placeholder */}
                      <span className="font-semibold text-lg">
                        {post.user.name[0]}
                      </span>
                    </div>
                    <h2 className="text-sm font-semibold truncate">
                      {post.title}
                    </h2>
                  </div>
                  <div className="text-gray-500 text-sm mb-2">
                    Created by: {post.user.name} on{" "}
                    {new Date(post.created_at).toLocaleTimeString()}
                  </div>
                  <div className="text-sm text-gray-600">
                    <strong>Category:</strong> {post.category}
                  </div>
                </div>
              </div>
            );
          })}
        </div>
      ) : (
        <p>There are no posts.</p>
      )}
    </Layout>
  );
}
