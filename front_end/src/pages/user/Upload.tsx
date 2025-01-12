import { useContext, useEffect, useState } from "react";
import { AppContext } from "../../context/AppContext";
import { useNavigate } from "react-router-dom";
import Layout from "../../components/layouts/Layout"; // Import Layout component

export default function Upload() {
  const { user, token } = useContext(AppContext);
  const navigate = useNavigate();

  useEffect(() => {
    if (user) {
      navigate("/upload");
    }
  }, [user, navigate]);

  const handleGoBack = () => {
    navigate("/artworks");
  };

  const [formData, setFormData] = useState({
    title: "",
    description: "",
    category: "",
    image: null as File | null,
  });
  async function uploadPost(e: React.FormEvent) {
    e.preventDefault(); // Prevent form submission

    // Create a new FormData instance
    const formDataToSend = new FormData();

    // Append form fields (using state values) to FormData instance
    formDataToSend.append("title", formData.title);
    formDataToSend.append("description", formData.description);
    formDataToSend.append("category", formData.category);
    if (formData.image) {
      formDataToSend.append("image", formData.image);
    }

    for (const [key, value] of formDataToSend.entries()) {
      if (value instanceof File) {
        console.log(`${key}: [File: ${value.name}, ${value.size} bytes]`);
      } else {
        console.log(`${key}: ${value}`);
      }
    }

    // Send data using fetch with FormData
    const res = await fetch("/api/posts", {
      method: "POST",
      headers: {
        Authorization: `Bearer ${token}`,
      },
      body: formDataToSend, // Send FormData object
    });

    const data = await res.json();
    console.log(data);
  }

  return (
    <Layout>
      {/* Dashboard Content */}

      <div className="mt-16">
        {/* Your dashboard content here */}
        <p>Welcome to the Upload!</p>
        <button onClick={handleGoBack}>Go to Artworks</button>
      </div>

      <div>
        <h1>Create Post</h1>

        <form
          onSubmit={uploadPost}
          className="space-y-6"
          encType="multipart/form-data"
        >
          <div>
            <label
              htmlFor="title"
              className="block text-sm font-medium text-gray-700"
            >
              Title
            </label>
            <input
              value={formData.title}
              onChange={(e) =>
                setFormData({ ...formData, title: e.target.value })
              }
              name="title"
              type="text"
              placeholder="Title"
              className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            />
          </div>

          <div>
            <label
              htmlFor="description"
              className="block text-sm font-medium text-gray-700"
            >
              Description
            </label>
            <textarea
              value={formData.description}
              onChange={(e) =>
                setFormData({ ...formData, description: e.target.value })
              }
              name="description"
              placeholder="Description"
              className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            ></textarea>
          </div>

          <div>
            <label
              htmlFor="category"
              className="block text-sm font-medium text-gray-700"
            >
              Category
            </label>
            <select
              name="category"
              id="category"
              value={formData.category}
              onChange={(e) =>
                setFormData({ ...formData, category: e.target.value })
              }
              className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            >
              <option value="Painting">Painting</option>
              <option value="Sculpture">Sculpture</option>
              <option value="Modern Art">Modern Art</option>
            </select>
          </div>

          <div>
            <label
              htmlFor="file"
              className="block text-sm font-medium text-gray-700"
            >
              Choose Image
            </label>
            <input
              type="file"
              name="file"
              onChange={(e) =>
                setFormData({
                  ...formData,
                  image: e.target.files ? e.target.files[0] : null,
                })
              }
              className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            />
          </div>

          <button
            type="submit"
            className="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
          >
            Upload Post
          </button>
        </form>
      </div>
    </Layout>
  );
}
