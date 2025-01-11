import { ReactNode, useContext } from "react";
import { Link, useNavigate } from "react-router-dom";
import { AppContext } from "/vags_capstone/front_end/src/context/AppContext";

interface LayoutProps {
  children: ReactNode;
}

const Layout = ({ children }: LayoutProps) => {
  const { token, setUser, setToken } = useContext(AppContext);
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
    <div className="flex h-screen bg-white">
      <header className="h-10 p-3 bg-gray-100 fixed w-full top-0 left-0 z-10">
        <p>Hello </p>
      </header>
      {/* Sidebar */}
      <aside className="w-48 h-full bg-white border-r-2 border-gray-200 p-5 flex flex-col gap-4 fixed top-0 left-0 mt-16">
        <Link
          to="/dashboard"
          className="text-gray-800 text-lg font-medium p-2 rounded hover:bg-gray-100 hover:text-red-500 transition"
        >
          Dashboard
        </Link>
        <Link
          to="/artworks"
          className="text-gray-800 text-lg font-medium p-2 rounded hover:bg-gray-100 hover:text-red-500 transition"
        >
          My Artworks
        </Link>
        <Link
          to="/messages"
          className="text-gray-800 text-lg font-medium p-2 rounded hover:bg-gray-100 hover:text-red-500 transition"
        >
          Messages
        </Link>
        <Link
          to="/exhibits"
          className="text-gray-800 text-lg font-medium p-2 rounded hover:bg-gray-100 hover:text-red-500 transition"
        >
          Exhibits
        </Link>
        <Link
          to="/settings"
          className="text-gray-800 text-lg font-medium p-2 rounded hover:bg-gray-100 hover:text-red-500 transition"
        >
          Settings
        </Link>
        <form action="" onSubmit={handleLogout} className="mt-5">
          <button
            type="submit"
            className="text-red-500 text-lg font-medium p-2 rounded hover:bg-gray-100 transition"
          >
            Logout
          </button>
        </form>
      </aside>

      {/* Main Content Area */}
      <main className="ml-32 mt-10 bg-white flex-1">{children}</main>
    </div>
  );
};

export default Layout;
