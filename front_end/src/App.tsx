import { SinglePageLayout } from "./pages/SinglePageLayout";
import { Login } from "./pages/Login";
import { SignUp } from "./pages/SignUp";
import "./styles/global.css";
import { Routes, Route } from "react-router-dom";
import Dashboard from "./pages/user/Dashboard";
import MyArtwork from "./pages/user/MyArtworks";
import Upload from "./pages/user/Upload";
import { useContext } from "react";
import { AppContext } from "./context/AppContext";
import Layout from "./components/layouts/Layout";

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
        <Route
          path="/dashboard"
          element={
            <Layout>
              <Dashboard />
            </Layout>
          }
        />
        <Route
          path="/artworks"
          element={
            <Layout>
              <MyArtwork />
            </Layout>
          }
        />
        <Route
          path="/upload"
          element={
            <Layout>
              <Upload />
            </Layout>
          }
        />
      </Routes>
    </main>
  );
}
