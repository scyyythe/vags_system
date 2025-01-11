import { useContext, useEffect } from "react";
import { AppContext } from "../../context/AppContext";
import { useNavigate } from "react-router-dom";
import Layout from "../../components/layouts/Layout"; // Import Layout component

export default function Dashboard() {
  const { user } = useContext(AppContext);
  const navigate = useNavigate();

  useEffect(() => {
    if (user) {
      navigate("/dashboard");
    }
  }, [user, navigate]);

  return <Layout>{<p>Hello Works</p>}</Layout>;
}
