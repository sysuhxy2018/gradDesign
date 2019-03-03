public class CopyArray{
    public static void main(String[] args){
        int[] sa = {1, 2, 3, 4, 5, 6};
        int[] ta = new int[10];

        /*实际使用的时候，有几个注意事项：
        1. srcPos和tarPos必须合法，不越界。
        2. 从srcPos开始的length长度的子数组必须完全包含在sourceArray的范围内，即不会有元素越界。
        3. 从tarPos开始的length长度的子数组必须完全包含在targetArray的范围内，即不会有元素越界。
        */
        
        System.arraycopy(sa, 2, ta, 5, sa.length - 2);
        printArray(ta);
    }

    public static void printArray(int[] arr){
        for (int i = 0; i < arr.length; i++){
            System.out.print(arr[i] + " ");
        }
        System.out.println();
    }

}