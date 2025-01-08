import { useContext } from "react";
import { AppContext } from "../context/AppContext";
import { Link, useNavigate } from "react-router-dom";

export default function Dashboard() {
  const { user, token, setUser, setToken } = useContext(AppContext);
  const navigate = useNavigate();

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
      {user ? (
        <div>
          <p className="text-white">hello {user.name}</p>

          <form action="" onSubmit={handleLogout}>
            <button
              type="submit"
              className="text-customRed font-medium hover:text-customRed"
            >
              Logout
            </button>
          </form>
        </div>
      ) : (
        <Link
          to="/signup"
          className="text-customRed font-medium hover:text-customRed"
        >
          Sign Up ↑
        </Link>
      )}
    </div>
  );
}
