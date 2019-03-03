public class JudgePrimeNumber{
    public static void main(String[] args){
        
        //目标：分5行显示前50个素数，每行包括10个数字。
        final int NUMBEROFPRIMES = 50;
        final int NUMBERPERLINE = 10;

        int count = 0;
        int number = 2;
        while (count < NUMBEROFPRIMES) {

            //Judge the Prime Number
            boolean isPrime = true;
            for (int i = 2; i <= number / 2; i++) {
                if (number % i == 0) {
                    isPrime = false;
                    break;
                }
            }
            if (isPrime) {
                count++;
                System.out.print(number + (count % NUMBERPERLINE == 0 ? "\n" : " "));
            }
            number++;
        }
    }
}