package token

import "testing"

func TestFactory(t *testing.T) {
	token := Factory()
	println(token)
}

func BenchmarkFactory(b *testing.B) {
	for i := 0; i < b.N; i++ {
		Factory()
	}
}
