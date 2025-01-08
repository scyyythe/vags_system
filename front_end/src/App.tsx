import { SinglePageLayout } from "./pages/SinglePageLayout";
import { Login } from "./pages/Login";
import { SignUp } from "./pages/SignUp";
import "./styles/global.css";
import { Routes, Route } from "react-router-dom";
import Dashboard from "./pages/Dashboard";
import { useContext } from "react";
import { AppContext } from "./context/AppContext";

export default function App() {
  const { user } = useContext(AppContext);

  const handleSectionChange = (section: string) => {
    // Implement the logic to handle section change if needed
  };

  return (
    <main className="bg-black">
      <Routes>
        <Route path="/" element={<SinglePageLayout />} />
        <Route
          path="/login"
          element={
            user ? (
              <Dashboard />
            ) : (
              <Login onSectionChange={handleSectionChange} />
            )
          }
        />
        <Route
          path="/signup"
          element={
            user ? (
              <Dashboard />
            ) : (
              <SignUp onSectionChange={handleSectionChange} />
            )
          }
        />
        <Route path="/dashboard" element={<Dashboard />} />
      </Routes>
    </main>
  );
}
