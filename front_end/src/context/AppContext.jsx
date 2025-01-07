import { createContext, useEffect, useState } from "react";

export const AppContext = createContext();

export default function AppContextProvider({ children }) {
    const [token, setToken] = useState(localStorage.getItem("token") || "");  // Corrected the typo
    const [user, setUser] = useState(null);  // Corrected initialization

    async function getUser() {
        const response = await fetch('api/user', {
            headers: {
                Authorization: `Bearer ${token}`  // Corrected the syntax for string interpolation
            }
        });
        const data = await response.json();
        setUser(data);
    }

    useEffect(() => {
        if (token) {
            getUser();
        }
    }, [token]);

    return (
        <AppContext.Provider value={{ token, setToken, user }}>
            {children}
        </AppContext.Provider>
    );
}
