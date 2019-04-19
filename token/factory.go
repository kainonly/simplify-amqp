package token

import (
	"github.com/dgrijalva/jwt-go"
	"time"
)

type CustomClaims struct {
	jwt.StandardClaims
}

func Factory() string {
	hmacSampleSecret := []byte("WgjCA%1n8ZidI1Qt")

	token := jwt.NewWithClaims(jwt.SigningMethodHS256, CustomClaims{
		jwt.StandardClaims{
			Audience:  "somebody",
			ExpiresAt: time.Now().Unix(),
			Id:        "123",
			IssuedAt:  time.Now().Unix(),
			Issuer:    "kain",
			NotBefore: time.Now().Unix(),
			Subject:   "asd",
		},
	})

	tokenString, _ := token.SignedString(hmacSampleSecret)

	return tokenString
}
