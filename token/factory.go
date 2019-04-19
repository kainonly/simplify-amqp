package token

import (
	"github.com/dgrijalva/jwt-go"
	"time"
)

func Factory() string {
	hmacSampleSecret := []byte("WgjCA%1n8ZidI1Qt")

	token := jwt.NewWithClaims(jwt.SigningMethodHS256, jwt.MapClaims{
		"foo": "bar",
		"nbf": time.Now().Unix(),
	})

	tokenString, _ := token.SignedString(hmacSampleSecret)

	return tokenString
}
