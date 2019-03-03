public class Epsilon{
    public static void main(String[] args){
        final double EPSILON = 1E-14;
        double x = 1.0 - 0.1 - 0.1 - 0.1 - 0.4;
        if (Math.abs(x - 0.5) < EPSILON)
            System.out.println(x + " is approximately 0.5");
        //其实比较大小只需要直接比较就行了，但是注意要先排除掉近似相等的范畴
        if(x > 0.5 && Math.abs(x - 0.5) >= EPSILON)
            System.out.println(x + " is larger than 0.5");
        if(x < 0.5 && Math.abs(x - 0.5) >= EPSILON)
            System.out.println(x + " is smaller than 0.5");
    }
}