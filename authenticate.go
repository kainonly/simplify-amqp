package main

import (
	"github.com/dgrijalva/jwt-go"
	"github.com/segmentio/ksuid"
	"time"
)

type (
	Symbol map[string]interface{}
)

func main() {
	SetToken()
}

func SetToken() {
	var hmacSampleSecret = []byte("e8NhjiHq86IVfy3nLqN9IdriYryyBPX4K7gNwAaU")
	jti := ksuid.New().String()
	token := jwt.NewWithClaims(jwt.SigningMethodHS256, jwt.MapClaims{
		"jti":    jti,
		"aud":    "",
		"iss":    "",
		"exp":    time.Now().Unix() + 1800,
		"user":   "",
		"role":   "",
		"symbol": Symbol{},
	})

	tokenString, err := token.SignedString(hmacSampleSecret)
	if err != nil {
		println(err.Error())
	}
	println(tokenString)
}

func CheckToken() {

}
