import { useContext, useState } from "react";
import { Link } from "react-router-dom";
import { AuthLayout } from "../components/layouts/AuthLayout";
import { Input } from "../components/ui/Input";
import { Button } from "../components/ui/Button";
import { Modal } from "../components/ui/Modal";
import { useNavigate } from "react-router-dom";
import { AppContext } from "../context/AppContext";

export function Login({
  onSectionChange,
}: {
  onSectionChange: (section: string) => void;
}) {
  const navigate = useNavigate();
  // token
  const { setToken } = useContext(AppContext);

  const [errors, setErrors] = useState({
    username: "",
    password: "",
  });

  const [isModalVisible, setModalVisible] = useState(false);
  const [rememberMe, setRememberMe] = useState(false);
  const validateField = (name: string, value: string) => {
    let error = "";
    switch (name) {
      case "username":
        if (!value.trim()) error = "Username is required.";
        else if (value.length < 4)
          error = "Username must be at least 4 characters.";
        break;
      case "password":
        if (!value.trim()) error = "Password is required.";
        else if (value.length < 6)
          error = "Password must be at least 6 characters.";
        break;
      default:
        break;
    }
    return error;
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    const form = e.target as HTMLFormElement;
    const username = form.username.value;
    const password = form.password.value;

    const newErrors = {
      username: validateField("username", username),
      password: validateField("password", password),
    };

    setErrors(newErrors);

    const hasErrors = Object.values(newErrors).some((error) => error);

    if (!hasErrors) {
      try {
        // Send login request to the backend
        const response = await fetch("/api/login", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ username, password, remember: rememberMe }),
        });

        const data = await response.json();
        console.log(data);
        if (response.ok) {
          localStorage.setItem("token", data.token);
          setToken(data.token);
          setModalVisible(true);
          form.reset();
          // setTimeout(() => {
          //   navigate("/dashboard");
          // }, 2000);
        } else {
          setErrors({
            username: data.username || "'",
            password: data.password || "Invalid Credentials",
          });
        }
      } catch (error) {
        console.error("Login error:", error);
        setModalVisible(true);
        setErrors({
          username: "An error occurred. Please try again later.",
          password: "",
        });
      }
    }
  };

  return (
    <AuthLayout onSectionChange={onSectionChange} statueOnRight={false}>
      <div className="max-w-lg mx-auto w-full">
        <div className="absolute top-10 right-4 md:right-20 p-7 text-right">
          <span className="text-gray-700">Not a member? </span>

          <Link
            to="/signup"
            className="text-customRed font-medium hover:text-customRed"
          >
            Sign Up ↑
          </Link>
        </div>
        <h1 className="text-5xl font-bold text-black mb-10 text-center">
          Welcome Back!
        </h1>

        <div className="flex space-x-10 mb-8 ml-[-40px] justify-center w-[600px]">
          <Button
            variant="google"
            className="w-[290px] text-white hover:bg-red-700 shadow-[0_5px_4px_0_rgba(0,0,0,0.3)]"
          >
            <img src="/google.svg" alt="Google" className="w-5 h-5 mr-2" />
            with Google
          </Button>
          <Button
            variant="facebook"
            className="w-[290px] shadow-[0_5px_4px_0_rgba(0,0,0,0.3)]"
          >
            <img src="/facebook.svg" alt="Facebook" className="w-5 h-5 mr-2" />
            with Facebook
          </Button>
        </div>

        <div className="relative my-6 ml-[-40px] flex items-center w-[600px]">
          <div className="flex-grow border-t border-gray-700"></div>
          <span className="px-4 text-sm text-black">Or</span>
          <div className="flex-grow border-t border-gray-700"></div>
        </div>

        <form className="space-y-6 ml-[-40px]" onSubmit={handleSubmit}>
          <Input
            type="text"
            id="username"
            label="Username"
            placeholder="Enter your username"
            error={errors.username}
          />
          <Input
            type="password"
            id="password"
            label="Password"
            placeholder="Enter your password"
            error={errors.password}
          />
          <div className="flex items-center justify-between text-sm w-[600px]">
            <label className="flex items-center">
              <input
                name="remember"
                id="remember"
                type="checkbox"
                checked={rememberMe}
                onChange={() => setRememberMe(!rememberMe)}
                className="rounded border-gray-600 text-customRed focus:ring-customRed"
              />
              <span className="ml-2 text-gray-700">Remember Me</span>
            </label>
            <a href="#" className="text-customRed hover:text-red-600">
              Forgot password?
            </a>
          </div>
          <Button type="submit" className="w-[600px] text-xl">
            Sign In
          </Button>
        </form>

        {isModalVisible && (
          <Modal
            message="You've logged in successfully!"
            onClose={() => setModalVisible(false)}
          />
        )}
      </div>
    </AuthLayout>
  );
}
