import { useContext, useEffect } from "react";
import { AppContext } from "../../context/AppContext";
import { useNavigate } from "react-router-dom";
import Layout from "../../components/layouts/Layout"; // Import Layout component
import { Link } from "react-router-dom";

export default function Artworks() {
  const { user } = useContext(AppContext);
  const navigate = useNavigate();

  useEffect(() => {
    if (user) {
      navigate("/artworks");
    }
  }, [user, navigate]);

  return (
    <Layout>
      {/* Dashboard Content */}

      <div className="mt-16">
        {/* Your dashboard content here */}
        <p>Welcome to the Artworks!</p>
        <Link to="/upload">Post</Link>
      </div>
    </Layout>
  );
}
