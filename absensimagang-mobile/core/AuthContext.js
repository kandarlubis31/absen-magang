import React, {
    createContext,
    useContext,
    useEffect,
    useMemo,
    useState,
} from "react";
import { deleteToken, getToken, saveToken } from "./storage";

const AuthContext = createContext(null);

export function useAuth() {
    return useContext(AuthContext);
}

export function AuthProvider({ children }) {
    const [userToken, setUserToken] = useState(null);
    const [isLoading, setIsLoading] = useState(true);

    useEffect(() => {
        async function loadToken() {
            try {
                const token = await getToken();
                if (token) {
                    setUserToken(token);
                }
            } catch (e) {
                console.error("Failed to load token", e);
            } finally {
                setIsLoading(false);
            }
        }
        loadToken();
    }, []);

    const signIn = async (token) => {
        setUserToken(token);
        await saveToken(token);
    };

    const signOut = async () => {
        setUserToken(null);
        await deleteToken();
    };

    const value = useMemo(
        () => ({
            signIn,
            signOut,
            userToken,
            isLoading,
        }),
        [userToken, isLoading]
    );

    return (
        <AuthContext.Provider value={value}>{children}</AuthContext.Provider>
    );
}
