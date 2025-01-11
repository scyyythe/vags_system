import { useContext, useEffect } from "react";
import { AppContext } from "../../context/AppContext";
import { useNavigate } from "react-router-dom";
import Layout from "../../components/layouts/Layout"; // Import Layout component

export default function Upload() {
  const { user } = useContext(AppContext);
  const navigate = useNavigate();

  useEffect(() => {
    if (user) {
      navigate("/upload");
    }
  }, [user, navigate]);
  const handleGoBack = () => {
    navigate("/artworks"); // Navigate to the artworks page
  };
  return (
    <Layout>
      {/* Dashboard Content */}

      <div className="mt-16">
        {/* Your dashboard content here */}
        <p>Welcome to the Upload!</p>
        <button onClick={handleGoBack}>Go to Artworks</button>
      </div>
    </Layout>
  );
}
