import java.util.*;

public class IOtest{
    public static void main(String[] args){
        Scanner input = new Scanner(System.in);
        int index;
        //用hasNext()方法也可以，但hasNextInt()会更严谨
        while(input.hasNextInt()){
            index = input.nextInt();
            System.out.println("This is NO." + index);
            double showNum;
            for(int i = 0; i < 2; i++){
                showNum = input.nextDouble();
                System.out.println("Input " + showNum);
            }
        }
    }
}