import { Link } from "react-router-dom";
import { AuthLayout } from "../components/layouts/AuthLayout";
import { Input } from "../components/ui/Input";
import { Button } from "../components/ui/Button";
import { useContext, useState } from "react";
import { Modal } from "../components/ui/Modal";
import { useNavigate } from "react-router-dom";
import { AppContext } from "../context/AppContext";

export function SignUp({
  onSectionChange,
}: {
  onSectionChange: (section: string) => void;
}) {
  // token
  const { setToken } = useContext(AppContext);

  const [errors, setErrors] = useState({
    name: "",
    username: "",
    email: "",
    password: "",
    password_confirmation: "",
  });

  const [isModalVisible, setModalVisible] = useState(false);
  const [isChecked, setIsChecked] = useState(false);
  const [checkboxError, setCheckboxError] = useState("");
  const navigate = useNavigate();

  const validateField = (name: string, value: string) => {
    let error = "";
    switch (name) {
      case "name": {
        const nameParts = value.trim().split(" ");
        if (!value.trim()) error = "Name is required.";
        else if (nameParts.length < 2) error = "Please enter your full name.";
        break;
      }
      case "username": {
        if (!value.trim()) error = "Username is required.";
        else if (value.length < 4)
          error = "Username must be at least 4 characters.";
        break;
      }
      case "email": {
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zAZ]{2,}$/;
        if (!value.trim()) error = "Email is required.";
        else if (!emailPattern.test(value))
          error = "Please enter a valid email address.";
        break;
      }
      case "password": {
        const passwordLength = /^(?=.{6,})/;
        const lowercaseLetter = /[a-z]/;
        const uppercaseLetter = /[A-Z]/;
        const digit = /\d/;
        const specialCharacter = /[!@#$%^&*(),.?":{}|<>_]/;
        const forbiddenCharacters = /['"\s]/;

        if (!value.trim()) error = "Password is required.";
        else if (!passwordLength.test(value))
          error = "Password must be at least 6 characters long.";
        else if (!lowercaseLetter.test(value))
          error = "Password must contain at least one lowercase letter.";
        else if (!uppercaseLetter.test(value))
          error = "Password must contain at least one uppercase letter.";
        else if (!digit.test(value))
          error = "Password must contain at least one number.";
        else if (!specialCharacter.test(value))
          error =
            "Password must contain at least one special character (!@#$%^&*).";
        else if (forbiddenCharacters.test(value))
          error = "Password cannot contain spaces or quotes.";
        break;
      }
      case "password_confirmation": {
        if (
          value !==
          (document.getElementById("password") as HTMLInputElement)?.value
        ) {
          error = "Passwords do not match.";
        }
        break;
      }
      default:
        break;
    }

    return error;
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    const form = e.target as HTMLFormElement;

    // Get form values
    const name = (document.getElementById("name") as HTMLInputElement).value;
    const username = (document.getElementById("username") as HTMLInputElement)
      .value;
    const email = (document.getElementById("email") as HTMLInputElement).value;
    const password = (document.getElementById("password") as HTMLInputElement)
      .value;
    const password_confirmation = (
      document.getElementById("password_confirmation") as HTMLInputElement
    ).value;

    // Log form values to check if they are being captured
    console.log("Form data:", {
      name,
      username,
      email,
      password,
      password_confirmation,
    });

    // Validate fields
    const newErrors = {
      name: validateField("name", name),
      username: validateField("username", username),
      email: validateField("email", email),
      password: validateField("password", password),
      password_confirmation: validateField(
        "password_confirmation",
        password_confirmation
      ),
    };

    setErrors(newErrors);

    const hasErrors = Object.values(newErrors).some((error) => error);

    // Validate checkbox
    if (!isChecked) {
      setCheckboxError("You must agree to the terms and Privacy Policy.");
    } else {
      setCheckboxError("");
    }

    // Modal validation
    if (!isChecked || hasErrors) {
      return;
    }

    try {
      console.log("Sending data to backend...");

      // Send data to backend
      const response = await fetch("/api/register", {
        method: "POST",
        body: JSON.stringify({
          name,
          username,
          email,
          password,
          password_confirmation,
        }),
      });

      const data = await response.json();

      // Log response data to check if the response is being received
      console.log("Response data:", data);

      if (response.ok) {
        localStorage.setItem("token", data.token);
        setToken(data.token);
        form.reset();
        setModalVisible(true);
        setTimeout(() => {
          navigate("/login");
        }, 2000);
      } else {
        // Handle API errors without modifying the errors state
        alert(data.message || "Something went wrong. Please try again.");
      }
    } catch (error) {
      // Handle network or other unexpected errors without modifying the errors state
      console.error("Error during submission:", error); // Log error to console
      alert("An error occurred while submitting the form. Please try again.");
    }
  };

  const handleCheckboxChange = () => {
    setIsChecked((prevChecked) => {
      const newChecked = !prevChecked;
      if (!newChecked) {
        setCheckboxError("You must agree to the terms and Privacy Policy.");
      } else {
        setCheckboxError("");
      }
      return newChecked;
    });
  };

  return (
    <AuthLayout onSectionChange={onSectionChange}>
      <div className="max-w-lg w-full mx-auto">
        <div className="absolute top-10 left-4 md:left-[700px] p-7 text-right">
          <span className="text-gray-700">Already a member? </span>
          <Link
            to="/login"
            className="text-customRed font-medium hover:text-customRed"
          >
            Sign In ↑
          </Link>
        </div>
        <h1 className="text-5xl font-bold text-black pt-20 mb-10 text-center">
          Create Account
        </h1>

        <div className="flex space-x-10 mb-8 ml-[-40px] justify-center w-[600px]">
          <Button
            variant="google"
            className="w-[300px] text-white hover:bg-red-700 shadow-[0_5px_4px_0_rgba(0,0,0,0.3)]"
          >
            <img src="/google.svg" alt="Google" className="w-5 h-5 mr-2" />
            with Google
          </Button>
          <Button
            variant="facebook"
            className="w-[300px] shadow-[0_5px_4px_0_rgba(0,0,0,0.3)]"
          >
            <img src="/facebook.svg" alt="Facebook" className="w-5 h-5 mr-2" />
            with Facebook
          </Button>
        </div>

        <div className="relative my-6 flex ml-[-40px] items-center w-[600px]">
          <div className="flex-grow border-t border-gray-700"></div>
          <span className="px-4 text-sm text-black">
            Or sign up using your email address
          </span>
          <div className="flex-grow border-t border-gray-700"></div>
        </div>

        <form className="space-y-6 ml-[-40px]" onSubmit={handleSubmit}>
          <Input
            type="text"
            id="name"
            label="Name"
            placeholder="Enter your full name"
            error={errors.name}
          />
          <Input
            type="email"
            id="email"
            label="Email Address"
            placeholder="Enter your email or phone number"
            error={errors.email}
          />
          <Input
            type="text"
            id="username"
            label="Username"
            placeholder="Choose a username"
            error={errors.username}
          />
          <Input
            type="password"
            id="password"
            label="Password"
            placeholder="Choose a password"
            error={errors.password}
          />
          <Input
            type="password"
            id="password_confirmation"
            label="Confirm Password"
            placeholder="Confirm password"
            error={errors.password_confirmation}
          />
          <label className="flex items-center text-sm">
            <input
              type="checkbox"
              className="rounded border-gray-600 text-customRed focus:ring-customRed"
              checked={isChecked}
              onChange={handleCheckboxChange}
            />
            <span className="ml-2 text-gray-700">
              I agree to all terms and Privacy Policy
            </span>
          </label>
          {checkboxError && (
            <p className="text-red-600 text-sm relative top-[-15px]">
              {checkboxError}
            </p>
          )}
          <Button type="submit" className="w-[600px] text-xl">
            Sign Up
          </Button>
        </form>

        {isModalVisible && (
          <Modal
            message="Your account has been created successfully!"
            onClose={() => setModalVisible(false)}
          />
        )}
      </div>
    </AuthLayout>
  );
}
