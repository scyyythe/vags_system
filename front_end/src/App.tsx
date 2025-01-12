import { SinglePageLayout } from "./pages/SinglePageLayout";
import { Login } from "./pages/Login";
import { SignUp } from "./pages/SignUp";
import "./styles/global.css";
import { Routes, Route } from "react-router-dom";
import Dashboard from "./pages/user/Dashboard";
import MyArtwork from "./pages/user/MyArtworks";
import Upload from "./pages/user/Upload";
import { useContext, useEffect } from "react";
import { AppContext } from "./context/AppContext";
import Layout from "./components/layouts/Layout";

export default function App() {
  const { user } = useContext(AppContext);

  const handleSectionChange = (section: string) => {
    console.log(`Section changed to: ${section}`);
  };

  useEffect(() => {
    // Disable right-click context menu

    // Disable text selection globally
    const disableTextSelection = () => {
      document.body.style.userSelect = "none";
    };

    // Block PrintScreen (Screenshot)
    const handleKeydown = (e: KeyboardEvent) => {
      if (e.key === "PrintScreen") {
        e.preventDefault();
        alert("Screenshots are disabled on this website.");
      }

      // Block specific key combinations (e.g., Ctrl + Shift + S, or Snipping Tool shortcuts)
      if (e.ctrlKey && e.shiftKey && e.key === "S") {
        e.preventDefault();
        alert("Screenshot tools are disabled.");
      }
    };

    // Detect Clipboard Copy (For Screenshot Prevention)
    const detectClipboardCopy = (event: ClipboardEvent) => {
      alert("Copying content is disabled.");
      event.preventDefault();
    };

    // Detect Screen Recording (Basic Detection)
    const detectScreenRecording = () => {
      navigator.mediaDevices.enumerateDevices().then((devices) => {
        const hasScreenRecording = devices.some(
          (device) => device.kind === "videoinput"
        );
        if (hasScreenRecording) {
          alert("Screen recording detected! Please stop recording.");
        }
      });
    };

    // Add Event Listeners

    document.addEventListener("keydown", handleKeydown);
    document.addEventListener("copy", detectClipboardCopy);

    // Apply global restrictions
    disableTextSelection();
    detectScreenRecording();

    // Clean up event listeners
    return () => {
      document.removeEventListener("keydown", handleKeydown);
      document.removeEventListener("copy", detectClipboardCopy);
    };
  }, []);

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
