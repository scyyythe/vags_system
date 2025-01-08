import { useContext, useEffect } from "react";
import { AppContext } from "../context/AppContext";
import { useNavigate } from "react-router-dom";

export default function Dashboard() {
  const { user, token, setUser, setToken } = useContext(AppContext);
  const navigate = useNavigate();
  useEffect(() => {
    // Navigate to login if user is null
    if (!user) {
      navigate("/login");
    }
  }, [user, navigate]);
  async function handleLogout(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();

    if (!token) {
      console.error("No token found");
      return;
    }

    const res = await fetch("/api/logout", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      },
    });

    const data = await res.json();
    console.log(data);

    if (res.ok) {
      setUser(null);
      setToken(null);
      localStorage.removeItem("token");
      setTimeout(() => navigate("/login"), 100);
    } else {
      console.error("Logout failed:", data);
    }
  }

  return (
    <div>
      {user && (
        <div>
          <p className="text-white">Hello {user.name}</p>

          <form action="" onSubmit={handleLogout}>
            <button
              type="submit"
              className="text-customRed font-medium hover:text-customRed"
            >
              Logout
            </button>
          </form>
        </div>
      )}
    </div>
  );
}
