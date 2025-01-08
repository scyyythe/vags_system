import { SinglePageLayout } from "./pages/SinglePageLayout";
import { Login } from "./pages/Login";
import { SignUp } from "./pages/SignUp";
import "./styles/global.css";
import { Routes, Route } from "react-router-dom";
import Dashboard from "./pages/Dashboard";

export default function App() {
  const handleSectionChange = (section: string) => {
    // Implement the logic to handle section change if needed
  };

  return (
    <main className="bg-black">
      <Routes>
        <Route path="/" element={<SinglePageLayout />} />
        <Route
          path="/login"
          element={<Login onSectionChange={handleSectionChange} />}
        />
        <Route
          path="/signup"
          element={<SignUp onSectionChange={handleSectionChange} />}
        />
        <Route path="/dashboard" element={<Dashboard />} />
      </Routes>
    </main>
  );
}
