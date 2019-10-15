
package StudentManagementSystem;

/**
 *
 * @author Radi
 */
public class Status {
    private int status;
    
    public Status(int status){
        this.status=status;
    }
    public String showStatus(){
        String  studentStatus="";
        if(status==3){
            studentStatus="Freshman";
        }else if(status==2){
             studentStatus="Junior";
        }else if(status==1){
             studentStatus="Senior";
        }else{
            System.out.println("You are not a student");
        }  
        return studentStatus;
    }
}
